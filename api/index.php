<?php

// Vercel serverless entry point — bootstrap temp dirs, copy SQLite DB, then forward to Laravel.

// Vercel's filesystem is read-only except /tmp.
// Laravel needs writable directories for views, cache, sessions, and logs.
$dirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Copy pre-built SQLite database to writable /tmp on cold start.
$dbSource = __DIR__ . '/../database/database.sqlite';
$dbDest   = '/tmp/database.sqlite';
if (!file_exists($dbDest) && file_exists($dbSource)) {
    copy($dbSource, $dbDest);
}

// Point Laravel's storage to writable /tmp paths.
$_ENV['VIEW_COMPILED_PATH']  = '/tmp/views';
$_ENV['LOG_CHANNEL']         = 'stderr';
$_ENV['SESSION_DRIVER']      = 'cookie';
$_ENV['CACHE_STORE']         = 'array';
$_ENV['DB_CONNECTION']       = 'sqlite';
$_ENV['DB_DATABASE']         = $dbDest;

require __DIR__ . '/../public/index.php';
