<?php

namespace App\Services\Menu\Items;

use App\Services\Menu\MenuItem;

/**
 * @Oracode Menu Item: Benefits Management
 * 🎯 Purpose: Menu item for managing certificate benefits
 * 🧱 Core Logic: Provides access to benefits CRUD operations
 *
 * @package App\Services\Menu\Items
 * @version 1.0
 */
class BenefitsManagementMenu extends MenuItem
{
    public function __construct()
    {
        parent::__construct(
            'Gestione Benefici',           // translationKey
            'founders.benefits.index',     // route
            'star',                        // icon
            null,                          // permission
            null,                          // children
            null,                          // modalAction
            [],                            // routeParams
            true                           // requiresWallet
        );
    }
}
