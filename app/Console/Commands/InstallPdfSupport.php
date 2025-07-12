<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class InstallPdfSupport extends Command
{
    protected $signature = 'pdf:install';
    protected $description = 'Installa il supporto PDF per i certificati FlorenceEGI';

    public function handle()
    {
        $this->info('🏛️ Installazione supporto PDF per FlorenceEGI...');

        try {
            // Verifica se DomPDF è già installato
            if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                $this->info('✅ DomPDF è già installato!');
                return 0;
            }

            $this->info('📦 Installazione DomPDF...');

            // Esegui composer require
            $process = new Process(['composer', 'require', 'barryvdh/laravel-dompdf']);
            $process->setTimeout(300); // 5 minuti
            $process->run();

            if ($process->isSuccessful()) {
                $this->info('✅ DomPDF installato con successo!');
                $this->info('📜 Ora puoi generare i PDF dei certificati rinascimentali.');
                return 0;
            } else {
                $this->error('❌ Errore durante l\'installazione di DomPDF');
                $this->error($process->getErrorOutput());
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore: ' . $e->getMessage());
            $this->info('💡 Prova a eseguire manualmente: composer require barryvdh/laravel-dompdf');
            return 1;
        }
    }
}
