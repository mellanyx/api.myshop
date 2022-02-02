<?php

/** @phpcsSuppress */

declare(strict_types=1);

error_reporting(E_ALL);

require '../vendor/autoload.php';

use Mellanyx\StandaloneContainerRouter\System\Application;

try {
    $application = new Application();
    $application->run();
} catch (Throwable $exception) {
    die($exception->getMessage());
}
