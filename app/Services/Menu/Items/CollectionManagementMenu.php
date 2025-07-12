<?php

namespace App\Services\Menu\Items;

use App\Services\Menu\MenuItem;

/**
 * Menu item per la gestione delle collection di certificati
 *
 * @package App\Services\Menu\Items
 */
class CollectionManagementMenu extends MenuItem
{
    public function __construct()
    {
        parent::__construct(
            translationKey: 'menu.collection_management',
            route: 'founders.collections.index',
            icon: 'new_collection',
            permission: null,
            children: null,
            modalAction: null,
            routeParams: [],
            requiresWallet: true
        );
    }
}
