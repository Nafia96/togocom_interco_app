<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NationalInvoice;
use PDF; // barryvdh/laravel-dompdf
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;

class InvoiceExportController extends Controller
{
    /**
     * Generate PDF or Word for a national invoice
     * URL: /national_invoice/{id}/generate/{format?}
     */
    public function generate($id, $format = 'pdf')
    {
        $inv = NationalInvoice::findOrFail($id);

        // lines are stored as JSON
        $lines = json_decode($inv->lines_json, true) ?: [];

        // Prepare view data
        $data = [
            'invoice' => $inv,
            'details' => $lines,
        ];

        // Ensure output directory exists (public/facture)
        $outDir = public_path('facture');
        if (!is_dir($outDir)) {
            @mkdir($outDir, 0755, true);
        }

        $baseName = 'national_invoice_' . $inv->id . '_' . time();

        // Generate PDF
        if ($format === 'pdf') {
            try {
                $pdf = PDF::loadView('invoices.template', $data)->setPaper('a4', 'portrait');
                $pdfPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.pdf';
                $pdf->save($pdfPath);
                // Update model facture_name
                $inv->facture_name = basename($pdfPath);
                $inv->save();
                return response()->download($pdfPath);
            } catch (\Throwable $e) {
                // Fallback: render HTML and return
                $html = view('invoices.template', $data)->render();
                $htmlPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.html';
                file_put_contents($htmlPath, $html);
                $inv->facture_name = basename($htmlPath);
                $inv->save();
                return response()->download($htmlPath);
            }
        }

        // Generate Word (.docx) using PhpWord template if available
        if ($format === 'word' || $format === 'docx') {
            try {
                $templatePath = resource_path('templates/facture_template.docx');
                if (!file_exists($templatePath)) {
                    // Create a simple fallback docx by saving HTML as .docx
                    $outputPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.docx';
                    $html = view('invoices.template', $data)->render();
                    file_put_contents($outputPath, $html);
                    $inv->facture_name = basename($outputPath);
                    $inv->save();
                    return response()->download($outputPath);
                }

                $template = new TemplateProcessor($templatePath);
                // Set simple placeholders
                $template->setValue('direction', $inv->direction ?? '');
                $template->setValue('period', $inv->period ?? '');
                $template->setValue('invoice_date', $inv->invoice_date ?? '');

                // For table rows we expect template using cloneRow
                $rows = [];
                foreach ($lines as $line) {
                    $rows[] = [
                        'mois' => $line['period'] ?? '',
                        'volume' => number_format($line['traffic_validated'] ?? $line['volume'] ?? 0, 2, ',', ' '),
                        'pu' => $line['unit_price'] ?? '',
                        'montant_ht' => number_format($line['valorisation'] ?? 0, 2, ',', ' '),
                        'montant_ttc' => number_format(($line['valorisation'] ?? 0) * 1.18, 2, ',', ' '),
                    ];
                }

                // If there are rows, clone
                if (!empty($rows)) {
                    $template->cloneRowAndSetValues('mois', $rows);
                }

                $outputPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.docx';
                $template->saveAs($outputPath);
                $inv->facture_name = basename($outputPath);
                $inv->save();
                return response()->download($outputPath);
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Erreur lors de la génération Word: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Format non supporté');
    }
}
