<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use App\Models\Contestation;
use App\Models\Creditnote;
use App\Models\Invoice;
use App\Models\Journal;
use App\Models\Operation;
use App\Models\Operator;
use App\Models\Resum;
use App\Models\User;
use App\Models\Measure;
use App\Models\UnitPrice;
use App\Models\MeasureValidationAudit;
use App\Models\NationalInvoice;
use Illuminate\Http\Request;

class NationalController extends Controller
{
    //Togotelecom - tgc view
    public function show_tgt_tgc()
    {
        $measures = Measure::where('direction', 'TGT->TGC')->orderBy('period', 'desc')->get();

        // Also gather corresponding measures for the opposite direction (TGC->TGT) for comparison
        $measures_tgcc = Measure::where('direction', 'TGC->TGT')->orderBy('period', 'desc')->get();

        $currentYear = date('Y');

        // Helper to compute sums (yearly and total) for a collection and a numeric field
        $computeSums = function ($collection, $field) use ($currentYear) {
            $total = 0.0;
            $yearTotal = 0.0;
            foreach ($collection as $m) {
                $val = floatval($m->{$field} ?? 0);
                $total += $val;
                // consider period containing the year string as belonging to that year
                if (strpos((string)$m->period, (string)$currentYear) !== false) {
                    $yearTotal += $val;
                }
            }
            return ['year' => $yearTotal, 'total' => $total];
        };

        // For TogoTelecom measures we display m_tgc as the measured traffic
        $tgt_tgc_sums = $computeSums($measures, 'm_tgc');

        // Ecart = diff (m_tgc - m_tgt)
        $ecart_sums = $computeSums($measures, 'diff');

        // Togococel measures (TGC->TGT) show m_tgt or m_tgc? Use m_tgt as their measured inbound
        $togococel_sums = $computeSums($measures_tgcc, 'm_tgt');

        return view('national.tgt_tgc_dashboard', compact(
            'measures',
            'measures_tgcc',
            'tgt_tgc_sums',
            'ecart_sums',
            'togococel_sums'
        ));
    }

    /**
     * Generic viewer for any direction. Pass the direction as URL segment (e.g. TGT->TGC).
     */
    public function show_by_direction($direction)
    {
        // direction may be URL-encoded; normalize
        $direction = urldecode($direction);

        // If we have a simple route for this direction, redirect to it for cleaner URLs
        $route = $this->directionToRoute($direction);
        if ($route) {
            return redirect()->route($route);
        }

        $measures = Measure::where('direction', $direction)->orderBy('period', 'desc')->get();

        // Reuse the same Blade; it expects $measures. If you have separate blades per direction, adjust accordingly.
        return view('national.tgt_tgc_dashboard', compact('measures'));
    }

    public function mesure_tgt_tgc(Request $request)
    {
        $request->validate([
            'm_tgc' => 'required|numeric|between:0,99999999999999999999.99',
            'm_tgt' => 'required|numeric|between:0,99999999999999999999.99',
        ]);

        $data = $request->all();
        //dd($data);

        $m_tgc = floatval($request->m_tgc);
        $m_tgt = floatval($request->m_tgt);
        $diff = $m_tgc - $m_tgt;
        $pct = $m_tgt > 0 ? ($diff / $m_tgt) * 100 : 0;
        // Prevent DB out-of-range for pct_diff (migration defines decimal(8,4))
        $maxPct = 9999.9999;
        if (!is_finite($pct) || abs($pct) > $maxPct) {
            $pct = ($pct < 0 ? -1 : 1) * $maxPct;
        }

        // Prevent duplicate for same direction+period
        $period = $request->periode;
        $direction = $request->direction ?? 'TGT->TGC';
        if (Measure::where('direction', $direction)->where('period', $period)->exists()) {
            return redirect()->back()->with('error', 'Une mesure pour cette période et cette direction existe déjà.')->withInput();
        }

        try {
            $measure = Measure::create([
                'period' => $request->periode,
                'm_tgt' => $m_tgt,
                'm_tgc' => $m_tgc,
                'diff' => $diff,
                'pct_diff' => $pct,
                'comment' => $request->comment ?? null,
                'direction' => $direction,
                'created_by' => auth()->id() ?? null,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Duplicate entry or other DB error - flash an error so layout shows SweetAlert
            return redirect()->back()->with('error', 'Impossible d\'ajouter la mesure : une entrée existe déjà pour cette période/direction.')->withInput();
        }

        // If difference is small (< 2%), automatically set traffic_validated = measured (m_tgc)
        if (abs($pct) < 2.0) {
            $measure->traffic_validated = $m_tgc;

            // determine unit price as in set_validated_traffic
            $up = UnitPrice::where('direction', $measure->direction)
                ->where('period', $measure->period)
                ->orderBy('effective_from', 'desc')
                ->first();

            if (!$up) {
                $up = UnitPrice::where('direction', $measure->direction)->orderBy('effective_from', 'desc')->first();
            }
            $price = $up ? floatval($up->price) : 0;
            $measure->valuation = $price * $m_tgc;
            $measure->save();
        }

        // Redirect to a simple named route for the direction when available
        $route = $this->directionToRoute($measure->direction);
        if ($route) {
            return redirect()->route($route)->with('success', 'Mesure ajoutée avec succès.');
        }
        $encoded = urlencode($measure->direction);
        return redirect()->route('show_measure', ['direction' => $encoded])->with('success', 'Mesure ajoutée avec succès.');
    }

    /**
     * Generic store for measures. Expects a `direction` field in the request.
     */
    public function mesure_store(Request $request)
    {
        $request->validate([
            'm_tgc' => 'required|numeric|between:0,99999999999999999999.99',
            'm_tgt' => 'required|numeric|between:0,99999999999999999999.99',
            'direction' => 'required|string'
        ]);

        $m_tgc = floatval($request->m_tgc);
        $m_tgt = floatval($request->m_tgt);
        $diff = $m_tgc - $m_tgt;
        $pct = $m_tgt > 0 ? ($diff / $m_tgt) * 100 : 0;
        // Clamp pct_diff to DB column precision to avoid out-of-range errors
        $maxPct = 9999.9999;
        if (!is_finite($pct) || abs($pct) > $maxPct) {
            $pct = ($pct < 0 ? -1 : 1) * $maxPct;
        }

        // Prevent duplicate for same direction+period
        $period = $request->periode;
        $direction = $request->direction;
        if (Measure::where('direction', $direction)->where('period', $period)->exists()) {
            return redirect()->back()->with('error', 'Une mesure pour cette période et cette direction existe déjà.')->withInput();
        }

        try {
            $measure = Measure::create([
                'period' => $request->periode,
                'm_tgt' => $m_tgt,
                'm_tgc' => $m_tgc,
                'diff' => $diff,
                'pct_diff' => $pct,
                'comment' => $request->comment ?? null,
                'direction' => $request->direction,
                'created_by' => auth()->id() ?? null,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // DB-level uniqueness or other error - flash an error for SweetAlert
            return redirect()->back()->with('error', 'Impossible d\'ajouter la mesure : une entrée existe déjà pour cette période/direction.')->withInput();
        }

        if (abs($pct) < 2.0) {
            $measure->traffic_validated = $m_tgc;

            $up = UnitPrice::where('direction', $measure->direction)
                ->where('period', $measure->period)
                ->orderBy('effective_from', 'desc')
                ->first();

            if (!$up) {
                $up = UnitPrice::where('direction', $measure->direction)->orderBy('effective_from', 'desc')->first();
            }
            $price = $up ? floatval($up->price) : 0;
            $measure->valuation = $price * $m_tgc;
            $measure->save();
        }

        // redirect back to the show page for that direction using simple route when possible
        $route = $this->directionToRoute($measure->direction);
        if ($route) {
            return redirect()->route($route)->with('success', 'Mesure ajoutée avec succès.');
        }
        $encoded = urlencode($measure->direction);
        return redirect()->route('show_measure', ['direction' => $encoded])->with('success', 'Mesure ajoutée avec succès.');
    }

    /**
     * Set manually the traffic validated for a measure, compute valuation using unit price for direction/period
     */
    public function set_validated_traffic(Request $request, $id)
    {
        $request->validate([
            'traffic_validated' => 'required|numeric|min:0'
        ]);

        $measure = Measure::findOrFail($id);
        $tv = floatval($request->traffic_validated);

        // Record audit before changing
        MeasureValidationAudit::create([
            'measure_id' => $measure->id,
            'changed_by' => auth()->id() ?? null,
            'old_value' => $measure->traffic_validated,
            'new_value' => $tv,
            'comment' => $request->validation_comment ?? null,
        ]);

        $measure->traffic_validated = $tv;

        // find unit price for this direction and period (exact match on period first, otherwise latest price)
        $up = UnitPrice::where('direction', $measure->direction)
            ->where('period', $measure->period)
            ->orderBy('effective_from', 'desc')
            ->first();

        if (!$up) {
            // fallback to most recent price for that direction
            $up = UnitPrice::where('direction', $measure->direction)->orderBy('effective_from', 'desc')->first();
        }

        $price = $up ? floatval($up->price) : 0;
        $measure->valuation = $price * $tv;
        $measure->save();

        // Try to redirect back to the measure's direction page if possible
        $route = $this->directionToRoute($measure->direction);
        if ($route) {
            return redirect()->route($route)->with('success', 'Trafic validé mis à jour.');
        }
        $encoded = urlencode($measure->direction);
        return redirect()->route('show_measure', ['direction' => $encoded])->with('success', 'Trafic validé mis à jour.');
    }

    /**
     * Update an existing measure row (edit action from the dashboard)
     */
    public function update_measure(Request $request, $id)
    {
        $request->validate([
            'm_tgc' => 'required|numeric|min:0',
            'm_tgt' => 'required|numeric|min:0',
            'periode' => 'required|string',
        ]);

        $measure = Measure::findOrFail($id);

        $m_tgc = floatval($request->m_tgc);
        $m_tgt = floatval($request->m_tgt);
        $diff = $m_tgc - $m_tgt;
        $pct = $m_tgt > 0 ? ($diff / $m_tgt) * 100 : 0;
        // Clamp pct_diff to the DB column maximum to prevent numeric overflow
        $maxPct = 9999.9999;
        if (!is_finite($pct) || abs($pct) > $maxPct) {
            $pct = ($pct < 0 ? -1 : 1) * $maxPct;
        }

        // Only check for duplicates if we're actually changing the period
        $period = $request->periode;
        if ($period !== $measure->period) {
            if (Measure::where('direction', $measure->direction)->where('period', $period)->exists()) {
                return redirect()->back()->with('error', 'Une autre mesure existe déjà pour cette période et cette direction.')->withInput();
            }
        }

        $measure->period = $period;
        $measure->m_tgc = $m_tgc;
        $measure->m_tgt = $m_tgt;
        $measure->diff = $diff;
        $measure->pct_diff = $pct;
        $measure->comment = $request->comment ?? $measure->comment;

        // Handle traffic validation
        $traffic_validated = $request->has('traffic_validated') ? floatval($request->traffic_validated) : null;

        if ($traffic_validated !== null) {
            // If traffic_validated is provided, use it and create audit
            $oldValue = $measure->traffic_validated;
            $measure->traffic_validated = $traffic_validated;

            // Create audit entry if value changed
            if ($oldValue !== $traffic_validated) {
                MeasureValidationAudit::create([
                    'measure_id' => $measure->id,
                    'changed_by' => auth()->id() ?? null,
                    'old_value' => $oldValue,
                    'new_value' => $traffic_validated,
                    'comment' => $request->validation_comment ?? null,
                ]);
            }

            // Calculate valuation with the validated traffic
            $up = UnitPrice::where('direction', $measure->direction)
                ->where('period', $measure->period)
                ->orderBy('effective_from', 'desc')
                ->first();

            if (!$up) {
                $up = UnitPrice::where('direction', $measure->direction)
                    ->orderBy('effective_from', 'desc')
                    ->first();
            }

            $price = $up ? floatval($up->price) : 0;
            $measure->valuation = $price * $traffic_validated;
        } elseif (abs($pct) < 2.0) {
            // Auto-validate/valorise when difference small
            $measure->traffic_validated = $m_tgc;
            $up = UnitPrice::where('direction', $measure->direction)
                ->where('period', $measure->period)
                ->orderBy('effective_from', 'desc')
                ->first();
            if (!$up) {
                $up = UnitPrice::where('direction', $measure->direction)->orderBy('effective_from', 'desc')->first();
            }
            $price = $up ? floatval($up->price) : 0;
            $measure->valuation = $price * $m_tgc;
        } else {
            // Clear validated traffic and valuation until manual validation
            $measure->traffic_validated = null;
            $measure->valuation = null;
        }

        $measure->save();

        $route = $this->directionToRoute($measure->direction);
        if ($route) {
            return redirect()->route($route)->with('success', 'Mesure mise à jour avec succès.');
        }
        $encoded = urlencode($measure->direction);
        return redirect()->route('show_measure', ['direction' => $encoded])->with('success', 'Mesure mise à jour avec succès.');

    }

    /**
     * Map a direction string to a simple named route if available.
     * Returns the route name (string) or null to indicate fallback to show_measure.
     */
    private function directionToRoute($direction)
    {
        $map = [
            'TGT->TGC' => 'tgt-tgc',
            'TGC->TGT' => 'tgc-tgt',
            'TGT->MAT' => 'tgt-mat',
            'MAT->TGT' => 'mat-tgt',
            // add other mappings here if you create more simple routes
        ];

        return $map[$direction] ?? null;
    }

    /**
     * Return JSON containing the measure's original comment and its validation audits.
     */
    public function get_measure_audits($id)
    {
        $measure = Measure::findOrFail($id);
        $audits = MeasureValidationAudit::where('measure_id', $measure->id)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'measure_comment' => $measure->comment,
            'audits' => $audits,
        ]);
    }

    /**
     * Generate an invoice PDF from selected measure IDs (posted as comma-separated ids)
     */
    public function generate_invoice_from_measures(Request $request)
    {
        // allow longer execution time for large invoice generation during testing
        @set_time_limit(300);

        $request->validate([
            'selected_ids' => 'required|string',
            'direction' => 'required|string'
        ]);

        $ids = array_filter(explode(',', $request->selected_ids));
        if (count($ids) === 0) {
            return redirect()->back()->with('error', 'Aucune ligne sélectionnée pour la génération de facture.');
        }

        $measures = Measure::whereIn('id', $ids)->where('direction', $request->direction)->get();
        if ($measures->count() === 0) {
            return redirect()->back()->with('error', 'Aucune mesure valide trouvée pour la direction sélectionnée.');
        }

        $lines = [];
        $totValor = 0;
        $totTtc = 0;

        foreach ($measures as $m) {
            // Determine validated volume (if present) or measured
            $useMeasured = abs(floatval($m->pct_diff)) < 2.0;
            $volume = $useMeasured ? floatval($m->m_tgc) : (floatval($m->traffic_validated) ?: 0);

            // Lookup unit price for direction + period, fallback to latest
            $up = UnitPrice::where('direction', $m->direction)->where('period', $m->period)->orderBy('effective_from', 'desc')->first();
            if (!$up) {
                $up = UnitPrice::where('direction', $m->direction)->orderBy('effective_from', 'desc')->first();
            }
            $unitPrice = $up ? floatval($up->price) : 0;

            $valorisation = $unitPrice * $volume;
            $ttc = $valorisation * 1.18; // 18% VAT

            $lines[] = [
                'period' => $m->period,
                'volume' => $volume,
                'unit_price' => $unitPrice,
                'valorisation' => $valorisation,
                'ttc' => $ttc,
            ];

            $totValor += $valorisation;
            $totTtc += $ttc;
        }

        // Create national invoice record (new table)
        $invoiceNumber = 'NINV-' . date('YmdHis') . '-' . rand(100,999);

        $nationalInvoice = NationalInvoice::create([
            'invoice_number' => $invoiceNumber,
            'direction' => $request->direction,
            'period' => $measures->first()->period,
            'periodDate' => periodeDate($measures->first()->period),
            'invoice_date' => date('Y-m-d'),
            'total_volume' => array_sum(array_column($lines, 'volume')),
            'total_valorisation' => $totValor,
            'total_ttc' => $totTtc,
            'lines_json' => json_encode($lines),
            'created_by' => auth()->id() ?? null,
            'comment' => 'Facture nationale générée depuis mesures',
        ]);

        // Render Blade to HTML
        $html = view('invoices.generated_invoice', [
            'lines' => $lines,
            'direction' => $request->direction,
            'totals' => ['valorisation' => $totValor, 'ttc' => $totTtc],
            'invoice_number' => $invoiceNumber,
            'logo_url' => url('/images/logo.png'),
        ])->render();

        // Save PDF if dompdf available, else save HTML file
        $fileNameBase = date('Y-m-d') . '_' . $invoiceNumber;
        $publicPath = public_path('facture');
        if (!file_exists($publicPath)) mkdir($publicPath, 0755, true);

        $pdfPath = $publicPath . '/' . $fileNameBase . '.pdf';
        $htmlPath = $publicPath . '/' . $fileNameBase . '.html';

        try {
            if (class_exists('\Barryvdh\DomPDF\Facade\Pdf') || class_exists('\Barryvdh\DomPDF\Facade\PDF')) {
                $pdf = app()->make('\Barryvdh\DomPDF\PDF');
                $pdf->loadHTML($html);
                file_put_contents($pdfPath, $pdf->output());
                $nationalInvoice->facture_name = '/facture/' . basename($pdfPath);
                $nationalInvoice->save();
            } else {
                // Fallback: save HTML
                file_put_contents($htmlPath, $html);
                $nationalInvoice->facture_name = '/facture/' . basename($htmlPath);
                $nationalInvoice->save();
            }
        } catch (\Exception $e) {
            // Save HTML on error
            file_put_contents($htmlPath, $html);
            $nationalInvoice->facture_name = '/facture/' . basename($htmlPath);
            $nationalInvoice->save();
        }

        return redirect()->back()->with('success', 'Facture nationale générée: ' . ($nationalInvoice->facture_name ?? 'N/A'));
    }

    /**
     * List national invoices
     */
    public function national_invoices_index()
    {
        $invoices = \App\Models\NationalInvoice::orderBy('created_at', 'desc')->get();
        return view('invoices.national_index', compact('invoices'));
    }

    /**
     * Download a generated national invoice file (pdf or html)
     */
    public function national_invoice_download($filename)
    {
        $path = public_path('facture/' . $filename);
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'Fichier introuvable: ' . $filename);
        }

    // Use the two-argument form (path, name) to let Laravel set a valid disposition string.
    return response()->download($path, $filename);
    }

    // Unit price management
    public function unit_prices_index()
    {
        $prices = UnitPrice::orderBy('direction')->orderBy('period', 'desc')->get();
        return view('national.unit_prices.index', compact('prices'));
    }

    public function unit_prices_store(Request $request)
    {
        $request->validate([
            'direction' => 'required|string',
            'period' => 'nullable|string',
            'price' => 'required|numeric|min:0'
        ]);

        UnitPrice::create([
            'direction' => $request->direction,
            'period' => $request->period,
            'price' => $request->price,
            'effective_from' => now(),
            'created_by' => auth()->id() ?? null,
        ]);

        return redirect()->route('unit_prices.index')->with('success', 'Prix unitaire enregistré.');
    }

    // Dedicated dashboards for specific sens
    public function show_tgc_tgt()
    {
        $measures = Measure::where('direction', 'TGC->TGT')->orderBy('period', 'desc')->get();
        return view('national.tgc_tgt_dashboard', compact('measures'));
    }

    public function show_tgt_mat()
    {
        $measures = Measure::where('direction', 'TGT->MAT')->orderBy('period', 'desc')->get();
        return view('national.tgt_mat_dashboard', compact('measures'));
    }

    public function show_mat_tgt()
    {
        $measures = Measure::where('direction', 'MAT->TGT')->orderBy('period', 'desc')->get();
        return view('national.mat_tgt_dashboard', compact('measures'));
    }

    public function show_mat_tgc()
    {
        $measures = Measure::where('direction', 'MAT->TGC')->orderBy('period', 'desc')->get();
        return view('national.mat_tgc_dashboard', compact('measures'));
    }



}
