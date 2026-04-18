<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/app_data.php';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/auth_components.php';
require_once __DIR__ . '/profile_components.php';
require_once __DIR__ . '/dashboard_components.php';
