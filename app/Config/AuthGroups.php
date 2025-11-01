<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * El grupo al que se asigna un usuario recién creado.
     */
    public string $defaultGroup = 'vendedor';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * Grupos disponibles en el sistema para La Bartola.
     *
     * @var array<string, array<string, string>>
     */
    public array $groups = [
        'admin' => [
            'title'       => 'Administrador',
            'description' => 'Control total del sistema. Puede crear usuarios.',
        ],
        'vendedor' => [
            'title'       => 'Vendedor',
            'description' => 'Gestiona pedidos y clientes del día a día.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * Permisos disponibles en el sistema.
     */
    public array $permissions = [
        // Permisos de usuarios
        'users.create'   => 'Puede crear nuevos usuarios',
        'users.edit'     => 'Puede editar usuarios',
        'users.delete'   => 'Puede eliminar usuarios',
        'users.view'     => 'Puede ver lista de usuarios',
        
        // Permisos de pedidos
        'orders.create'  => 'Puede crear pedidos',
        'orders.edit'    => 'Puede editar pedidos',
        'orders.delete'  => 'Puede eliminar pedidos',
        'orders.view'    => 'Puede ver pedidos',
        
        // Permisos de productos/menú
        'menu.create'    => 'Puede crear productos',
        'menu.edit'      => 'Puede editar productos',
        'menu.delete'    => 'Puede eliminar productos',
        'menu.view'      => 'Puede ver productos',
        
        // Permisos de clientes
        'clients.create' => 'Puede crear clientes',
        'clients.edit'   => 'Puede editar clientes',
        'clients.delete' => 'Puede eliminar clientes',
        'clients.view'   => 'Puede ver clientes',
        
        // Permisos de reportes
        'reports.view'   => 'Puede ver reportes',
        'reports.export' => 'Puede exportar reportes',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Mapeo de permisos a grupos.
     */
    public array $matrix = [
        'admin' => [
            // Admin tiene TODOS los permisos
            'users.*',
            'orders.*',
            'menu.*',
            'clients.*',
            'reports.*',
        ],
        'vendedor' => [
            // Vendedor tiene los mismos permisos por ahora (luego ajustar)
            'users.*',
            'orders.*',
            'menu.*',
            'clients.*',
            'reports.*',
        ],
    ];
}