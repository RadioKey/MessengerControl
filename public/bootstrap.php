<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// load configuration
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv
    ->usePutenv(true)
    ->loadEnv(__DIR__ . '/../.env');

// init container
$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->addDefinitions(require_once __DIR__ . '/../configs/services.php');

if (getenv('APP_ENV') === 'prod') {
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache/container');
}

$container = $containerBuilder->build();

// init app
$app = \DI\Bridge\Slim\Bridge::create($container);

// Add middlewares
$app->add(new \Zeuxisoo\Whoops\Slim\WhoopsMiddleware([
    'enable' => getenv('APP_ENV') !== 'prod',
]));

// configure routes
$app->get(
    '/messenger/callback/{messenger}',
    [\Radiokey\MessengerControl\Controller\MessengerCallbackController::class, 'handle']
);

// start app
$app->run();