<?php

namespace App\Services\Menu\Items;

use App\Services\Menu\MenuItem;

/**
 * Menu item per l'emissione di nuovi certificati
 *
 * @package App\Services\Menu\Items
 */
class CertificateCreateMenu extends MenuItem
{
    public function __construct()
    {
        parent::__construct(
            translationKey: 'menu.certificate_create',
            route: 'founders.certificates.create',
            icon: 'plus-circle',
            permission: null,
            children: null,
            modalAction: null,
            routeParams: [],
            requiresWallet: true
        );
    }
}