<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Operateur;
use App\Mail\FactureMailable;
use Illuminate\Support\Facades\Mail;

class EnvoyerFactures extends Command
{
    protected $signature = 'factures:envoyer';
    protected $description = 'Envoie les factures mensuelles aux opérateurs';

    public function handle()
    {
        $operateurs = Operateur::with(['emailPrincipal', 'emailsCc'])->get();

        foreach ($operateurs as $operateur) {
            $pdfPath = storage_path('app/' . $operateur->facture_path);

            if (!file_exists($pdfPath)) {
                $this->error("Facture introuvable pour l'opérateur {$operateur->nom}");
                continue;
            }

            $principal = optional($operateur->emailPrincipal)->email;
            $cc = $operateur->emailsCc->pluck('email')->toArray();

            if (!$principal) {
                $this->error("Pas d'email principal pour l'opérateur {$operateur->nom}");
                continue;
            }

            Mail::to($principal)
                ->cc($cc)
                ->send(new FactureMailable($pdfPath));

            $this->info("Facture envoyée à {$operateur->nom}");
        }

        $this->info('Envoi des factures terminé.');
    }
}
