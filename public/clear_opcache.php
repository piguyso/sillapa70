<?php
// OPcache clearing script (No key verification)
if (function_exists('opcache_reset')) {
    $success = opcache_reset();
    if ($success) {
        echo "OPcache cleared successfully.";
    } else {
        echo "OPcache reset failed.";
    }
} else {
    echo "OPcache is not enabled or opcache_reset is not available.";
}
