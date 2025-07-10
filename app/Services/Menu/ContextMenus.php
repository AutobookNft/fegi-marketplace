<?php

namespace App\Services\Menu;

use App\Services\Menu\Items\BackToDashboardMenu;
use App\Services\Menu\Items\OpenCollectionMenu;
use App\Services\Menu\Items\NewCollectionMenu;
// Nuovi item per i menu contestuali
use App\Services\Menu\Items\StatisticsMenu;
use App\Services\Menu\Items\CertificateIssueMenu;
use App\Services\Menu\Items\CertificateCreateMenu;
use App\Services\Menu\Items\TreasuryStatusMenu;
use App\Services\Menu\Items\ShippingManagementMenu;
use App\Services\Menu\Items\CollectionManagementMenu;
use Illuminate\Support\Facades\Log;

/**
 * @Oracode Service: Context-aware Menu Provider
 * 🎯 Purpose: Provides appropriate menus based on application context
 * 🧱 Core Logic: Factory method for context-specific menu groups
 *
 * @package App\Services\Menu
 * @version 2.0
 */
class ContextMenus
{
    /**
     * Get menu groups for specific application context
     *
     * @param string $context The current application context
     * @return array Array of MenuGroup objects for the context
     */
    public static function getMenusForContext(string $context): array
    {
       $menus = [];

       // Log the context for debugging purposes
       Log::channel('foundrising')->info('Getting menus for context', ['context' => $context]);

        switch ($context) {
            case 'dashboard':

                break;

            case 'founders':
                // Menu Gestione Certificati
                $certificatesMenu = new MenuGroup(__('menu.certificates'), 'certificate', [
                    new CertificateIssueMenu(),
                    new CertificateCreateMenu(),
                ]);
                $menus[] = $certificatesMenu;

                // Menu Treasury Management
                $treasuryMenu = new MenuGroup(__('menu.treasury'), 'wallet', [
                    new TreasuryStatusMenu(),
                ]);
                $menus[] = $treasuryMenu;

                // Menu Spedizioni
                $shippingMenu = new MenuGroup(__('menu.shipping'), 'truck', [
                    new ShippingManagementMenu(),
                ]);
                $menus[] = $shippingMenu;

                // Menu Collezioni (se necessario)
                $collectionsMenu = new MenuGroup(__('menu.collections'), 'folder_collection', [
                    new CollectionManagementMenu(),
                ]);
                $menus[] = $collectionsMenu;
                break;

        }

        return $menus;
    }
}
