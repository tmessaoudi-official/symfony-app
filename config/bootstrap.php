<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;

require __DIR__ . '/../vendor/autoload.php';

$dotEnv = new Dotenv();
$dotEnv->usePutenv(true);
$dotEnv->bootEnv(__DIR__ . '/../.env');
try {
    $dotEnv->bootEnv(__DIR__ . '/../.env.local');
} catch (Exception $exception) {
}
try {
    $dotEnv->bootEnv(__DIR__ . '/../.env.' . $_ENV['APP_ENV']);
} catch (Exception $exception) {
}
try {
    $dotEnv->bootEnv(__DIR__ . '/../.env.' . $_ENV['APP_ENV'] . '.local');
} catch (Exception $exception) {
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}
