#!/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// load configuration
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv
    ->usePutenv(true)
    ->loadEnv(__DIR__ . '/../.env');

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

/** @var \Symfony\Component\Console\Application $app */
$app = $container->get(\Symfony\Component\Console\Application::class);
$app->run();

