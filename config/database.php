<?php

use Illuminate\Support\Str;

$appName = env('APP_NAME', 'Fido');
$dbName = env('DB_DATABASE', 'database.sqlite');

// Determine if DB_DATABASE is already an absolute path
if (file_exists($dbName) || preg_match('/^([a-zA-Z]:)?[\\/]/', $dbName)) {
    // Absolute path or existing file, use as-is
    $databasePath = $dbName;
} else {
    // Construct OS-specific path inside user's AppData / Library / .config
    if (PHP_OS_FAMILY === 'Windows') {
        $appData = getenv('APPDATA');
        $databasePath = "{$appData}\\{$appName}\\{$dbName}";
    } elseif (PHP_OS_FAMILY === 'Darwin') { // macOS
        $home = getenv('HOME');
        $databasePath = "{$home}/Library/Application Support/{$appName}/{$dbName}";
    } else { // Linux
        $home = getenv('HOME');
        $databasePath = "{$home}/.config/{$appName}/{$dbName}";
    }
}

// Ensure directory exists
$dir = dirname($databasePath);
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

return [
    'default' => env('DB_CONNECTION', 'sqlite'),

    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => $databasePath,
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],
    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],
        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],
];
