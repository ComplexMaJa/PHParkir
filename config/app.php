<?php
/**
 * Application Configuration
 */

// Base URL - adjust if running in a subdirectory
define('BASE_URL', '/');

// Application name
define('APP_NAME', 'PHParkir');

// Session lifetime in seconds (2 hours)
define('SESSION_LIFETIME', 7200);

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
