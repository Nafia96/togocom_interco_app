#!/usr/bin/env python3
import re

filepath = "app/Http/Controllers/HomeController.php"

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Find the start of the method
method_start = content.find('public function billingPivotNetCarrier(Request $request)')
method_end = content.find('public function billingPivotCountryCarrier', method_start)

if method_start == -1 or method_end == -1:
    print("Methods not found")
    exit(1)

# Go back from method_end to find the closing brace
search_area = content[:method_end].rstrip()
last_closing_brace = search_area.rfind('}')

new_method = '''public function billingPivotNetCarrier(Request $request)
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

    '''

# Construct the new file
new_content = content[:method_start] + new_method + content[method_end:]

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(new_content)

print("File updated successfully!")
