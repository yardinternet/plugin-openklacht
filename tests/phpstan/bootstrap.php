<?php

declare(strict_types=1);

/**
 * Static-analysis bootstrap. Defines the runtime constants the plugin declares in
 * openklacht.php, which PHPStan cannot infer because they are defined inside a
 * conditionally-loaded file.
 */
define('OWC_OPENKLACHT_ROOT_PATH', __DIR__);
define('OWC_OPENKLACHT_PREFIX', 'okl');
