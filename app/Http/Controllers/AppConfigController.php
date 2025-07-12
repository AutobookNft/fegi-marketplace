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
            'menu.certificates' => 'Gestione Certificati',
            'menu.treasury' => 'Treasury Management',
            'menu.shipping' => 'Gestione Spedizioni',
            'menu.collections' => 'Gestione Collections',

            // Traduzioni per i menu item
            'menu.founders.certificate_issue' => 'Lista Certificati',
            'menu.founders.certificate_create' => 'Emetti Certificato',
            'menu.founders.treasury_status' => 'Status Treasury',
            'menu.founders.shipping_management' => 'Tracking Spedizioni',
            'menu.founders.collection_management' => 'Gestione Collections',

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

            // Traduzioni per le collections
            'collections.form.title' => 'Gestione Collection',
            'collections.form.name' => 'Nome Collection',
            'collections.form.description' => 'Descrizione',
            'collections.form.total_tokens' => 'Numero Totale Token',
            'collections.form.base_price' => 'Prezzo Base',
            'collections.form.currency' => 'Valuta',
            'collections.form.status' => 'Stato',
            'collections.form.event_date' => 'Data Evento',
            'collections.form.sale_start_date' => 'Inizio Vendita',
            'collections.form.sale_end_date' => 'Fine Vendita',
            'collections.form.metadata' => 'Metadata NFT',
            'collections.form.shipping_info' => 'Informazioni Spedizione',
            'collections.form.advanced_options' => 'Opzioni Avanzate',
            'collections.messages.created' => 'Collection creata con successo',
            'collections.messages.updated' => 'Collection aggiornata con successo',
            'collections.messages.deleted' => 'Collection eliminata con successo',
            'collections.messages.activated' => 'Collection attivata con successo',
            'collections.messages.paused' => 'Collection messa in pausa',
            'collections.messages.completed' => 'Collection completata',
            'collections.messages.cancelled' => 'Collection annullata',
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
