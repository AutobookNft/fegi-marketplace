<?php

namespace App\Services;

use App\Models\FounderCertificate;
use Illuminate\Support\Str;

/**
 * Service per generare certificati PDF legali professionali con TCPDF
 * Documenti ufficiali di alta qualità per stampa e archiviazione
 */
class LegalCertificateService
{
    private $tcpdf;
    private $pageHeight;
    private $pageWidth;
    private $leftMargin;
    private $rightMargin;
    private $topMargin;
    private $bottomMargin;

    public function __construct()
    {
        // Include TCPDF
        require_once(base_path('lib/TCPDF-main/tcpdf.php'));

        // Configura TCPDF per documento professionale
        $this->tcpdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Dimensioni pagina e margini professionali
        $this->pageWidth = 210;  // A4 width
        $this->pageHeight = 297; // A4 height
        $this->leftMargin = 25;
        $this->rightMargin = 25;
        $this->topMargin = 30;
        $this->bottomMargin = 30;

        // Impostazioni documento professionale
        $this->tcpdf->SetCreator('FlorenceEGI Legal Department');
        $this->tcpdf->SetAuthor('FlorenceEGI Certificate Authority');
        $this->tcpdf->SetTitle('Certificato di Fondazione FlorenceEGI - Documento Ufficiale');
        $this->tcpdf->SetSubject('Certificato Legale di Proprietà Blockchain');
        $this->tcpdf->SetKeywords('FlorenceEGI, Blockchain, Certificato, Legale, Algorand');

        // Rimuovi header e footer predefiniti
        $this->tcpdf->setPrintHeader(false);
        $this->tcpdf->setPrintFooter(false);

        // Margini professionali
        $this->tcpdf->SetMargins($this->leftMargin, $this->topMargin, $this->rightMargin);
        $this->tcpdf->SetAutoPageBreak(true, $this->bottomMargin);

        // Font di default professionale
        $this->tcpdf->SetFont('times', '', 12);
    }

    /**
     * Genera certificato PDF legale professionale
     */
    public function generateLegalCertificate(FounderCertificate $certificate): string
    {
        // Carica i benefits della collection
        $certificate->load('collection.certificateBenefits');

        // Aggiungi prima pagina
        $this->tcpdf->AddPage();

        // Documento Header
        $this->addDocumentHeader();

        // Titolo principale
        $this->addMainTitle();

        // Informazioni certificato
        $this->addCertificateInfo($certificate);

        // Dichiarazione ufficiale
        $this->addOfficialDeclaration($certificate);

        // Sezione benefits (se presenti)
        if ($certificate->collection && $certificate->collection->certificateBenefits->count() > 0) {
            $this->addBenefitsSection($certificate);
        }

        // Sezione blockchain
        $this->addBlockchainSection($certificate);

        // Sezione validità legale
        $this->addLegalValiditySection();

        // Firme e certificazioni
        $this->addSignatureSection($certificate);

        // Footer professionale
        $this->addDocumentFooter($certificate);

        // Genera il PDF
        $filename = $this->generateFilename($certificate);
        $path = storage_path("app/public/certificates/legal/{$filename}");

        // Crea directory se non esiste
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $this->tcpdf->Output($path, 'F');

        return "certificates/legal/{$filename}";
    }

    /**
     * Header professionale del documento
     */
    private function addDocumentHeader()
    {
        $this->tcpdf->SetY($this->topMargin);

        // Logo/Simbolo FlorenceEGI
        $this->tcpdf->SetFont('times', 'B', 24);
        $this->tcpdf->Cell(0, 15, 'FLORENCEEGI', 0, 1, 'C');

        // Sottotitolo
        $this->tcpdf->SetFont('times', '', 11);
        $this->tcpdf->Cell(0, 6, 'Il Nuovo Rinascimento Ecologico Digitale', 0, 1, 'C');

        // Linea decorativa
        $this->tcpdf->SetLineWidth(0.8);
        $this->tcpdf->Line($this->leftMargin, $this->tcpdf->GetY() + 5, $this->pageWidth - $this->rightMargin, $this->tcpdf->GetY() + 5);

        $this->tcpdf->Ln(10);
    }

    /**
     * Titolo principale del documento
     */
    private function addMainTitle()
    {
        $this->tcpdf->SetFont('times', 'B', 20);
        $this->tcpdf->Cell(0, 12, 'CERTIFICATO DI FONDAZIONE', 0, 1, 'C');

        $this->tcpdf->SetFont('times', '', 12);
        $this->tcpdf->Cell(0, 6, 'Documento Ufficiale di Proprietà Blockchain', 0, 1, 'C');

        $this->tcpdf->Ln(8);
    }

    /**
     * Informazioni del certificato in tabella professionale
     */
    private function addCertificateInfo(FounderCertificate $certificate)
    {
        $this->tcpdf->SetFont('times', 'B', 14);
        $this->tcpdf->Cell(0, 8, 'ARTICOLO I - INFORMAZIONI CERTIFICATO', 0, 1, 'L');
        $this->tcpdf->Ln(3);

        // Riquadro informativo
        $startY = $this->tcpdf->GetY();
        $boxHeight = 35;

        $this->tcpdf->SetLineWidth(0.5);
        $this->tcpdf->Rect($this->leftMargin, $startY, $this->pageWidth - $this->leftMargin - $this->rightMargin, $boxHeight);

        $this->tcpdf->SetY($startY + 5);

        // Tabella informazioni
        $this->tcpdf->SetFont('times', 'B', 11);
        $this->tcpdf->Cell(50, 6, 'Certificato N.:', 0, 0, 'L');
        $this->tcpdf->SetFont('times', '', 11);
        $this->tcpdf->Cell(0, 6, str_pad($certificate->id, 6, '0', STR_PAD_LEFT), 0, 1, 'L');

        $this->tcpdf->SetFont('times', 'B', 11);
        $this->tcpdf->Cell(50, 6, 'Data Emissione:', 0, 0, 'L');
        $this->tcpdf->SetFont('times', '', 11);
        $this->tcpdf->Cell(0, 6, $certificate->created_at->format('d F Y'), 0, 1, 'L');

        $this->tcpdf->SetFont('times', 'B', 11);
        $this->tcpdf->Cell(50, 6, 'Investitore:', 0, 0, 'L');
        $this->tcpdf->SetFont('times', '', 11);
        $this->tcpdf->Cell(0, 6, $certificate->investor_name ?? 'N/A', 0, 1, 'L');

        $this->tcpdf->SetFont('times', 'B', 11);
        $this->tcpdf->Cell(50, 6, 'Valore Nominale:', 0, 0, 'L');
        $this->tcpdf->SetFont('times', '', 11);
        $this->tcpdf->Cell(0, 6, '€ ' . number_format($certificate->base_price ?? 250, 2, ',', '.'), 0, 1, 'L');

        $this->tcpdf->SetY($startY + $boxHeight + 5);
        $this->tcpdf->Ln(5);
    }

    /**
     * Dichiarazione ufficiale
     */
    private function addOfficialDeclaration(FounderCertificate $certificate)
    {
        $this->tcpdf->SetFont('times', 'B', 14);
        $this->tcpdf->Cell(0, 8, 'ARTICOLO II - DICHIARAZIONE UFFICIALE', 0, 1, 'L');
        $this->tcpdf->Ln(3);

        $this->tcpdf->SetFont('times', '', 11);
        $text = "Con il presente documento, FlorenceEGI certifica ufficialmente che il soggetto sopra identificato è riconosciuto come PADRE FONDATORE del progetto FlorenceEGI, avendo contribuito significativamente al lancio e allo sviluppo dell'ecosistema blockchain dedicato alla sostenibilità ambientale e al nuovo rinascimento tecnologico.";

        $this->tcpdf->MultiCell(0, 6, $text, 0, 'J');
        $this->tcpdf->Ln(5);
    }

    /**
     * Sezione benefits professionale
     */
    private function addBenefitsSection(FounderCertificate $certificate)
    {
        $this->tcpdf->SetFont('times', 'B', 14);
        $this->tcpdf->Cell(0, 8, 'ARTICOLO III - PRIVILEGI E BENEFICI ESCLUSIVI', 0, 1, 'L');
        $this->tcpdf->Ln(3);

        $this->tcpdf->SetFont('times', '', 11);
        $this->tcpdf->Cell(0, 6, 'Il presente certificato conferisce i seguenti privilegi e benefici esclusivi:', 0, 1, 'L');
        $this->tcpdf->Ln(2);

        // Lista benefits
        foreach ($certificate->collection->certificateBenefits as $index => $benefit) {
            $this->tcpdf->SetFont('times', 'B', 11);
            $number = $index + 1;
            $this->tcpdf->Cell(10, 6, $number . '.', 0, 0, 'L');
            $this->tcpdf->Cell(0, 6, $benefit->title, 0, 1, 'L');

            $this->tcpdf->SetFont('times', '', 10);
            $this->tcpdf->Cell(10, 5, '', 0, 0, 'L');
            $this->tcpdf->MultiCell(0, 5, $benefit->description, 0, 'L');
            $this->tcpdf->Ln(2);
        }

        $this->tcpdf->Ln(3);
    }

    /**
     * Sezione blockchain professionale
     */
    private function addBlockchainSection(FounderCertificate $certificate)
    {
        $this->tcpdf->SetFont('times', 'B', 14);
        $this->tcpdf->Cell(0, 8, 'ARTICOLO IV - REGISTRAZIONE BLOCKCHAIN ALGORAND', 0, 1, 'L');
        $this->tcpdf->Ln(3);

        $startY = $this->tcpdf->GetY();
        $boxHeight = 40;

        $this->tcpdf->SetLineWidth(0.5);
        $this->tcpdf->Rect($this->leftMargin, $startY, $this->pageWidth - $this->leftMargin - $this->rightMargin, $boxHeight);

        $this->tcpdf->SetY($startY + 5);

        if ($certificate->asa_id) {
            $this->tcpdf->SetFont('times', 'B', 11);
            $this->tcpdf->Cell(50, 6, 'ASA Token ID:', 0, 0, 'L');
            $this->tcpdf->SetFont('courier', '', 10);
            $this->tcpdf->Cell(0, 6, $certificate->asa_id, 0, 1, 'L');
        }

        if ($certificate->tx_id) {
            $this->tcpdf->SetFont('times', 'B', 11);
            $this->tcpdf->Cell(50, 6, 'Transaction ID:', 0, 0, 'L');
            $this->tcpdf->SetFont('courier', '', 9);
            $this->tcpdf->Cell(0, 6, substr($certificate->tx_id, 0, 40) . '...', 0, 1, 'L');
        }

        if ($certificate->investor_wallet) {
            $this->tcpdf->SetFont('times', 'B', 11);
            $this->tcpdf->Cell(50, 6, 'Wallet Destinazione:', 0, 0, 'L');
            $this->tcpdf->SetFont('courier', '', 9);
            $this->tcpdf->Cell(0, 6, substr($certificate->investor_wallet, 0, 35) . '...', 0, 1, 'L');
        }

        $this->tcpdf->SetFont('times', 'B', 11);
        $this->tcpdf->Cell(50, 6, 'Network:', 0, 0, 'L');
        $this->tcpdf->SetFont('times', '', 11);
        $this->tcpdf->Cell(0, 6, 'Algorand Mainnet', 0, 1, 'L');

        $this->tcpdf->SetY($startY + $boxHeight + 5);
        $this->tcpdf->Ln(5);
    }

    /**
     * Sezione validità legale
     */
    private function addLegalValiditySection()
    {
        $this->tcpdf->SetFont('times', 'B', 14);
        $this->tcpdf->Cell(0, 8, 'ARTICOLO V - VALIDITÀ LEGALE E DIRITTI', 0, 1, 'L');
        $this->tcpdf->Ln(3);

        $this->tcpdf->SetFont('times', '', 11);
        $legal_text = "Il presente certificato costituisce documento ufficiale e prova legale della partecipazione qualificata al progetto FlorenceEGI. I diritti, privilegi e benefici associati sono definiti nei termini e condizioni del progetto e sono legalmente vincolanti. La registrazione su blockchain Algorand garantisce l'autenticità, l'immutabilità e la verificabilità del presente documento.";

        $this->tcpdf->MultiCell(0, 6, $legal_text, 0, 'J');
        $this->tcpdf->Ln(5);
    }

    /**
     * Sezione firme e certificazioni
     */
    private function addSignatureSection(FounderCertificate $certificate)
    {
        // Controlla se siamo vicini al fondo pagina
        if ($this->tcpdf->GetY() > $this->pageHeight - 80) {
            $this->tcpdf->AddPage();
        }

        $this->tcpdf->SetFont('times', 'B', 14);
        $this->tcpdf->Cell(0, 8, 'ARTICOLO VI - CERTIFICAZIONI E FIRME', 0, 1, 'L');
        $this->tcpdf->Ln(5);

        $startY = $this->tcpdf->GetY();

        // QR Code (sinistra)
        $qrX = $this->leftMargin;
        $qrY = $startY;
        $verificationUrl = url('/certificate/' . $certificate->id . '/' . $this->generatePublicHash($certificate));

        $this->tcpdf->write2DBarcode($verificationUrl, 'QRCODE,H', $qrX, $qrY, 25, 25,
            array('border' => false, 'vpadding' => 'auto', 'hpadding' => 'auto',
                  'fgcolor' => array(0, 0, 0), 'bgcolor' => array(255, 255, 255)));

        // Informazioni verifica (centro)
        $this->tcpdf->SetXY($qrX + 30, $qrY);
        $this->tcpdf->SetFont('times', 'B', 10);
        $this->tcpdf->Cell(80, 5, 'VERIFICA BLOCKCHAIN', 0, 1, 'L');
        $this->tcpdf->SetX($qrX + 30);
        $this->tcpdf->SetFont('times', '', 9);
        $this->tcpdf->Cell(80, 4, 'Scansiona il QR code per verificare', 0, 1, 'L');
        $this->tcpdf->SetX($qrX + 30);
        $this->tcpdf->Cell(80, 4, 'l\'autenticità del documento', 0, 1, 'L');

        // Firma digitale (destra)
        $signX = $this->pageWidth - $this->rightMargin - 50;
        $this->tcpdf->SetXY($signX, $qrY);
        $this->tcpdf->SetFont('times', '', 10);
        $this->tcpdf->Cell(50, 5, 'Direttore Generale', 0, 1, 'C');
        $this->tcpdf->SetX($signX);
        $this->tcpdf->SetFont('times', 'B', 12);
        $this->tcpdf->Cell(50, 8, 'Fabio Cherici', 0, 1, 'C');

        // Linea firma
        $this->tcpdf->Line($signX, $qrY + 20, $signX + 50, $qrY + 20);

        $this->tcpdf->SetY($startY + 35);
    }

    /**
     * Footer del documento
     */
    private function addDocumentFooter(FounderCertificate $certificate)
    {
        $this->tcpdf->SetY(-25);
        $this->tcpdf->SetFont('times', '', 8);

        // Linea separatrice
        $this->tcpdf->Line($this->leftMargin, $this->tcpdf->GetY(), $this->pageWidth - $this->rightMargin, $this->tcpdf->GetY());
        $this->tcpdf->Ln(3);

        // Informazioni documento
        $footer_text = 'Documento generato il ' . now()->format('d/m/Y H:i') . ' - Certificato ID: ' . str_pad($certificate->id, 6, '0', STR_PAD_LEFT) . ' - Pagina ' . $this->tcpdf->getPage();
        $this->tcpdf->Cell(0, 5, $footer_text, 0, 0, 'C');
    }

    /**
     * Genera hash pubblico per il QR code
     */
    private function generatePublicHash(FounderCertificate $certificate): string
    {
        $data = [
            'id' => $certificate->id,
            'investor_name' => $certificate->investor_name,
            'created_at' => $certificate->created_at?->timestamp,
            'collection_id' => $certificate->collection_id,
        ];

        return substr(hash('sha256', json_encode($data) . config('app.key')), 0, 16);
    }

    /**
     * Genera nome file
     */
    private function generateFilename(FounderCertificate $certificate): string
    {
        $number = str_pad($certificate->id, 4, '0', STR_PAD_LEFT);
        $name = Str::slug($certificate->investor_name ?? 'certificato');
        $date = now()->format('Y-m-d');

        return "certificato-legale-{$number}-{$name}-{$date}.pdf";
    }

    /**
     * Stream PDF direttamente al browser
     */
    public function streamLegalCertificate(FounderCertificate $certificate)
    {
        // Carica i benefits della collection
        $certificate->load('collection.certificateBenefits');

        // Aggiungi prima pagina
        $this->tcpdf->AddPage();

        // Documento Header
        $this->addDocumentHeader();

        // Titolo principale
        $this->addMainTitle();

        // Informazioni certificato
        $this->addCertificateInfo($certificate);

        // Dichiarazione ufficiale
        $this->addOfficialDeclaration($certificate);

        // Sezione benefits (se presenti)
        if ($certificate->collection && $certificate->collection->certificateBenefits->count() > 0) {
            $this->addBenefitsSection($certificate);
        }

        // Sezione blockchain
        $this->addBlockchainSection($certificate);

        // Sezione validità legale
        $this->addLegalValiditySection();

        // Firme e certificazioni
        $this->addSignatureSection($certificate);

        // Footer professionale
        $this->addDocumentFooter($certificate);

        // Output diretto
        $filename = $this->generateFilename($certificate);
        return $this->tcpdf->Output($filename, 'I');
    }
}
