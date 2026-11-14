<?php

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

$expectedToken = 'b3c7e01d96a54f28ad974b27c61e485f';
$providedToken = (string) ($_GET['token'] ?? '');

if (PHP_SAPI !== 'cli' && ! hash_equals($expectedToken, $providedToken)) {
    http_response_code(403);
    exit("Forbidden\n");
}

if (! function_exists('opcache_reset')) {
    http_response_code(503);
    exit("OPcache is not available\n");
}

if (! opcache_reset()) {
    http_response_code(500);
    exit("Unable to clear OPcache\n");
}

echo "OPcache cleared successfully\n";
