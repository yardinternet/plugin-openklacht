<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * The plugin defines these in openklacht.php, which is not loaded under test
 * because it bootstraps WordPress.
 */
define('OWC_OPENKLACHT_ROOT_PATH', dirname(__DIR__));
define('OWC_OPENKLACHT_PREFIX', 'okl');

WP_Mock::bootstrap();
