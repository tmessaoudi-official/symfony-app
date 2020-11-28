<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv())->bootEnv( __DIR__ . '/../.env');

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}
