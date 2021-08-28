<?php

use function DI\create;
use function DI\factory;
use function DI\get;
use Psr\Container\ContainerInterface;
use Slim\App;

return [
    'monolog.streamHandler' => factory(
        function (ContainerInterface $c) {
            /** @var App $app */
            $app = $c->get(App::class);

            return new \Monolog\Handler\StreamHandler(
                $app->getBasePath() . '/var/log/error.log',
                \Monolog\Logger::DEBUG
            );
        }
    ),
    \Psr\Log\LoggerInterface::class => factory(
        function (ContainerInterface $c) {
            $logger = new \Monolog\Logger('app');
            $logger->pushHandler($c->get('monolog.streamHandler'));
            return $logger;
        }
    )
];