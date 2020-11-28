<?php

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../../vendor/autoload.php';

(new Dotenv())->bootEnv(__DIR__ . '/../../.env');
(new Dotenv())->bootEnv(__DIR__ . '/../../.env.local');
(new Dotenv())->bootEnv(__DIR__ . '/../../.env.test');
(new Dotenv())->bootEnv(__DIR__ . '/../../.env.test.local');

passthru(sprintf(
    'APP_ENV=%s APP_DEBUG=%s php "%s/../../bin/console" c:c --no-warmup',
    $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'],
    $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'],
    __DIR__
));
