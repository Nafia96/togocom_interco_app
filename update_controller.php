<?php
$file = 'app/Http/Controllers/HomeController.php';
$original = file_get_contents($file);

// Find billingPivotNetCarrier method
$pattern = '/public function billingPivotNetCarrier\(Request \$request\)\s*\{[\s\S]*?\n    \}\s*\n\s*public function billingPivotCountryCarrier/';

$new_method = <<<'PHP'
public function billingPivotNetCarrier(Request $request)
    {
        $viewType = $request->input('view_type', 'day');
        $month    = $request->input('month', now()->format('Y-m'));
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $carrier   = $request->input('carrier_name');
        $filter    = strtolower($request->input('filter', 'entrant'));

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end   = Carbon::parse($endDate)->endOfDay();
        } else {
            if ($viewType === 'day') {
                [$year, $monthNum] = explode('-', $month);
                $start = Carbon::createFromDate($year, $monthNum, 1)->startOfDay();
                $end   = (clone $start)->endOfMonth()->endOfDay();
            } elseif ($viewType === 'month') {
                $start = now()->subMonths(11)->startOfMonth();
                $end   = now()->endOfMonth();
            } else {
                $start = now()->subYears(4)->startOfYear();
                $end   = now()->endOfYear();
            }
        }

        $isRevenue = in_array($filter, ['revenu', 'entrant']);
        $direction = $isRevenue ? 'revenue' : 'charge';
        $netColumn = $isRevenue ? 'orig_net_name' : 'dest_net_name';

        if (in_array($filter, ['revenu', 'charge'])) {
            $selectValue = DB::raw('SUM(CAST(amount_cfa AS DECIMAL(20,2))) as value');
        } else {
            $selectValue = DB::raw('SUM(CAST(minutes AS DECIMAL(20,6))) as value');
        }
        $valueLabel = in_array($filter, ['revenu', 'charge']) ? 'Montant CFA' : 'Minutes';

        $baseQuery = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->where('direction', $direction)
            ->whereBetween('start_date', [$start, $end]);

        if ($carrier) {
            $baseQuery->where('carrier_name', $carrier);
        }

        $allCarriers = DB::connection('inter_traffic')
            ->table('BILLING_STAT')
            ->distinct()
            ->orderBy('carrier_name')
            ->pluck('carrier_name');

        if ($viewType === 'day') {
            $records = $baseQuery->select([
                'direction',
                DB::raw('DATE(start_date) as period'),
                DB::raw("$netColumn as orig_net_name"),
                'carrier_name',
                $selectValue,
            ])
            ->groupBy('direction', DB::raw('DATE(start_date)'), $netColumn, 'carrier_name')
            ->orderBy('direction')
            ->orderBy('period')
            ->orderBy($netColumn)
            ->orderBy('carrier_name')
            ->get();

            $days = collect();
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $days->push($cursor->toDateString());
                $cursor->addDay();
            }

            return view('billing.billingPivotNetCarrier', [
                'records'     => $records,
                'days'        => $days,
                'month'       => $month,
                'startDate'   => $startDate,
                'endDate'     => $endDate,
                'allCarriers' => $allCarriers,
                'carrier'     => $carrier,
                'filter'      => $filter,
                'valueLabel'  => $valueLabel,
                'viewType'    => $viewType,
            ]);

        } elseif ($viewType === 'month') {
            $records = $baseQuery->select([
                'direction',
                DB::raw('YEAR(start_date) as year'),
                DB::raw('MONTH(start_date) as month'),
                DB::raw("$netColumn as orig_net_name"),
                'carrier_name',
                $selectValue,
            ])
            ->groupBy('direction', DB::raw('YEAR(start_date)'), DB::raw('MONTH(start_date)'), $netColumn, 'carrier_name')
            ->orderBy($netColumn)
            ->orderBy('carrier_name')
            ->get();

            $months = [];
            $cursor = $start->copy()->startOfMonth();
            while ($cursor->lte($end)) {
                $months[] = $cursor->format('Y-m');
                $cursor->addMonth();
            }

            return view('billing.billingPivotNetCarrierMonthly', [
                'records'     => $records,
                'months'      => $months,
                'month'       => $month,
                'startDate'   => $startDate,
                'endDate'     => $endDate,
                'allCarriers' => $allCarriers,
                'carrier'     => $carrier,
                'filter'      => $filter,
                'valueLabel'  => $valueLabel,
                'viewType'    => $viewType,
            ]);

        } else {
            $records = $baseQuery->select([
                'direction',
                DB::raw('YEAR(start_date) as year'),
                DB::raw("$netColumn as orig_net_name"),
                'carrier_name',
                $selectValue,
            ])
            ->groupBy('direction', DB::raw('YEAR(start_date)'), $netColumn, 'carrier_name')
            ->orderBy($netColumn)
            ->orderBy('carrier_name')
            ->get();

            $years = [];
            $cursor = $start->copy()->startOfYear();
            while ($cursor->lte($end)) {
                $years[] = $cursor->year;
                $cursor->addYear();
            }
            $years = array_unique($years);
            sort($years);

            return view('billing.billingPivotNetCarrierAnnual', [
                'records'     => $records,
                'years'       => $years,
                'month'       => $month,
                'startDate'   => $startDate,
                'endDate'     => $endDate,
                'allCarriers' => $allCarriers,
                'carrier'     => $carrier,
                'filter'      => $filter,
                'valueLabel'  => $valueLabel,
                'viewType'    => $viewType,
            ]);
        }
    }

    public function billingPivotCountryCarrier
PHP;

$replaced = preg_replace($pattern, $new_method, $original);

if ($replaced === $original) {
    echo "No match found!\n";
    exit(1);
}

file_put_contents($file, $replaced);
echo "File updated successfully!\n";
?>
