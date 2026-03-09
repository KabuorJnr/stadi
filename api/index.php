<?php

// Vercel serverless entry point — bootstrap temp dirs, then forward to Laravel.

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

// Point Laravel's storage to writable /tmp paths.
$_ENV['VIEW_COMPILED_PATH']  = '/tmp/views';
$_ENV['LOG_CHANNEL']         = 'stderr';
$_ENV['SESSION_DRIVER']      = 'cookie';
$_ENV['CACHE_STORE']         = 'array';

require __DIR__ . '/../public/index.php';
