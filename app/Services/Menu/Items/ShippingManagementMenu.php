<?php

namespace App\Services\Menu\Items;

use App\Services\Menu\MenuItem;

/**
 * Menu item per la gestione delle spedizioni
 *
 * @package App\Services\Menu\Items
 */
class ShippingManagementMenu extends MenuItem
{
    public function __construct()
    {
        parent::__construct(
            translationKey: 'menu.shipping_management',
            route: 'founders.shipping.index',
            icon: 'spedizione',
            permission: null,
            children: null,
            modalAction: null,
            routeParams: [],
            requiresWallet: true
        );
    }
}
