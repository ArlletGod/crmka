<?php

$db_config = require __DIR__ . '/config/database.php';

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeders'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => $db_config['driver'],
            'host' => $db_config['host'],
            'name' => $db_config['database'],
            'user' => $db_config['username'],
            'pass' => $db_config['password'],
            'port' => $db_config['port'],
            'charset' => $db_config['charset'],
        ]
    ],
    'version_order' => 'creation'
];
