<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Complete control of the site.',
        ],
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Day to day administrators of the site.',
        ],
        'vendedor' => [
            'title'       => 'Vendedor',
            'description' => 'Vendedor del negocio.',
        ],
        'cliente' => [
            'title'       => 'Cliente',
            'description' => 'Cliente registrado del negocio.',
        ],
    ];

    public array $permissions = [
        'admin.access'    => 'Can access the sites admin area',
        'admin.settings'  => 'Can access the main site settings',
        'users.manage'    => 'Can manage users',
        'vendedor.access' => 'Can access vendedor area',
        'cliente.access'  => 'Can access cliente area',
    ];

    public array $matrix = [
        'superadmin' => [
            'admin.*',
            'users.*',
            'vendedor.*',
            'cliente.*',
        ],
        'admin' => [
            'admin.access',
            'users.manage',
            'vendedor.access',
        ],
        'vendedor' => [
            'vendedor.access',
        ],
        'cliente' => [
            'cliente.access',
        ],
    ];
}