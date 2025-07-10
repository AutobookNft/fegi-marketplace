<?php

namespace App\Services\Menu\Items;

use App\Services\Menu\MenuItem;

/**
 * Menu item per lo status del Treasury wallet
 *
 * @package App\Services\Menu\Items
 */
class TreasuryStatusMenu extends MenuItem
{
    public function __construct()
    {
        parent::__construct(
            translationKey: 'menu.treasury_status',
            route: 'founders.treasury.index',
            icon: 'wallet',
            permission: null,
            children: null,
            modalAction: null,
            routeParams: [],
            requiresWallet: true
        );
    }
}