<?php

namespace App\Services\Menu\Items;

use App\Services\Menu\MenuItem;

/**
 * Menu item per l'emissione dei certificati Padre Fondatore
 *
 * @package App\Services\Menu\Items
 */
class CertificateIssueMenu extends MenuItem
{
    public function __construct()
    {
        parent::__construct(
            translationKey: 'menu.certificate_issue',
            route: 'founders.certificates.index',
            icon: 'certificate',
            permission: null,
            children: null,
            modalAction: null,
            routeParams: [],
            requiresWallet: true
        );
    }
}
