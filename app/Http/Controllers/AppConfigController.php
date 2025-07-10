<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller per la gestione delle traduzioni e configurazioni dell'applicazione
 *
 * @package App\Http\Controllers
 */
class AppConfigController extends Controller
{
    /**
     * Ottiene le traduzioni per il sistema Founders
     *
     * @return array
     */
    public function getFoundersTranslations(): array
    {
        return [
            // Traduzioni per i contesti
            'menu.founders' => 'Sistema Founders',
            
            // Traduzioni per i gruppi di menu
            'menu.founders_system' => 'Sistema Padri Fondatori',
            'menu.founders.certificates' => 'Gestione Certificati',
            'menu.founders.treasury' => 'Treasury Management',
            'menu.founders.shipping' => 'Gestione Spedizioni',
            'menu.founders.collections' => 'Gestione Collezioni',

            // Traduzioni per i menu item
            'menu.founders.certificate_issue' => 'Lista Certificati',
            'menu.founders.certificate_create' => 'Emetti Certificato',
            'menu.founders.treasury_status' => 'Status Treasury',
            'menu.founders.shipping_management' => 'Tracking Spedizioni',
            'menu.founders.collection_management' => 'Gestione Collection',

            // Traduzioni per i form
            'founders.form.title' => 'Emissione Certificato Padre Fondatore',
            'founders.form.investor_name' => 'Nome Investitore',
            'founders.form.investor_email' => 'Email Investitore',
            'founders.form.amount' => 'Importo (€)',
            'founders.form.payment_method' => 'Metodo di Pagamento',
            'founders.form.wallet_address' => 'Indirizzo Wallet (opzionale)',
            'founders.form.notes' => 'Note',
            'founders.form.submit' => 'Emetti Certificato',

            // Traduzioni per i messaggi
            'founders.messages.certificate_issued' => 'Certificato emesso con successo',
            'founders.messages.wallet_not_connected' => 'Wallet non connesso',
            'founders.messages.unauthorized_wallet' => 'Solo il wallet Treasury può accedere',
            'founders.messages.connection_required' => 'Connessione wallet richiesta',

            // Traduzioni per i pulsanti
            'founders.buttons.connect_wallet' => 'Connetti Wallet',
            'founders.buttons.disconnect_wallet' => 'Disconnetti Wallet',
            'founders.buttons.issue_certificate' => 'Emetti Certificato',
            'founders.buttons.view_certificates' => 'Visualizza Certificati',

            // Traduzioni per lo status
            'founders.status.wallet_connected' => 'Wallet Connesso',
            'founders.status.wallet_disconnected' => 'Wallet Disconnesso',
            'founders.status.treasury_balance' => 'Saldo Treasury',
            'founders.status.certificates_issued' => 'Certificati Emessi',
        ];
    }

    /**
     * Endpoint API per ottenere le traduzioni
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function translations(Request $request)
    {
        $translations = $this->getFoundersTranslations();

        return response()->json([
            'success' => true,
            'translations' => $translations
        ]);
    }
}
