<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'              => CSRF::class,
        'toolbar'           => DebugToolbar::class,
        'honeypot'          => Honeypot::class,
        'invalidchars'      => InvalidChars::class,
        'secureheaders'     => SecureHeaders::class,
        'cors'              => Cors::class,
        'forcehttps'        => ForceHTTPS::class,
        'pagecache'         => PageCache::class,
        'performance'       => PerformanceMetrics::class,
        'auth'              => \CodeIgniter\Shield\Filters\SessionAuth::class,
        'authAjax'          => \App\Filters\AuthAjaxFilter::class,
        'permission'        => \CodeIgniter\Shield\Filters\PermissionFilter::class,
        'group'             => \CodeIgniter\Shield\Filters\GroupFilter::class,
        'adminOrVendedor'   => \App\Filters\AdminOrVendedorFilter::class,
    ];

    public array $required = [
        'before' => [
            // 'forcehttps', // Solo activar en producción con SSL
            // 'pagecache', // Desactivado - puede causar lentitud en desarrollo
        ],
        'after' => [
            // 'pagecache', // Desactivado - puede causar lentitud en desarrollo
            // 'performance', // DESACTIVADO - Causa overhead de medición
            // 'toolbar', // DESACTIVADO - Causa lentitud de 3-5 segundos
        ],
    ];

    public array $globals = [
        'before' => [
            'invalidchars',
            // 'honeypot',
            // 'csrf', // CSRF se maneja por ruta específica
        ],
        'after' => [
            // 'toolbar', // DESACTIVADO - Causa lentitud de 3-5 segundos
            'secureheaders',
            // 'honeypot',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'group:admin' => [
            'before' => [
                'admin/*',
            ],
        ],
        'group:vendedor' => [
            'before' => [
                'vendedor/*',
            ],
        ],
    ];
}
