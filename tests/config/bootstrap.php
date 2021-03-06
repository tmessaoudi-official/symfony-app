<?php

/*
 * Personal project using Php 8/Symfony 5.2.x@dev.
 *
 * @author       : Takieddine Messaoudi <takieddine.messaoudi.official@gmail.com>
 * @organization : Smart Companion
 * @contact      : takieddine.messaoudi.official@gmail.com
 *
 */

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/../../vendor/autoload.php';

$dotEnv = new Dotenv();
$_ENV['APP_ENV'] = $_SERVER['APP_ENV'] = 'test';
$dotEnv->usePutenv(true);
$dotEnv->bootEnv(__DIR__.'/../../.env');

try {
    $dotEnv->bootEnv(__DIR__.'/../../.env.local');
} catch (Exception $exception) {
}

try {
    $dotEnv->bootEnv(__DIR__.'/../../.env.test');
} catch (Exception $exception) {
}

try {
    $dotEnv->bootEnv(__DIR__.'/../../.env.test.local');
} catch (Exception $exception) {
}

passthru(sprintf(
    'APP_ENV=%s APP_DEBUG=%s php "%s/../../bin/console" c:c --no-warmup',
    $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'],
    $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'],
    __DIR__
));
