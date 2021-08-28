<?php

use function DI\create;
use function DI\autowire;
use function DI\factory;
use function DI\get;
use Psr\Container\ContainerInterface;

return [
    'monolog.streamHandler' => factory(
        function (ContainerInterface $c) {
            return new \Monolog\Handler\StreamHandler(
                __DIR__ . '/../var/log/error.log',
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
    ),
    \Longman\TelegramBot\Telegram::class => factory(
        function(ContainerInterface $c) {
            return new Longman\TelegramBot\Telegram(
                getenv('TELEGRAM_BOT_API_TOKEN'),
                getenv('TELEGRAM_BOT_USERNAME'),
            );
        }
    ),
    \Radiokey\MessengerControl\Messenger\Client\MessengerClientInterface::class => autowire(
        \Radiokey\MessengerControl\Messenger\ClientAdapter\Telegram\Longman\LongmanTelegramBotClient::class
    ),
    \RadioKey\HubClient\Mqtt\MqttCommandPublisherInterface::class => factory(
        function (ContainerInterface $c) {
            return new \RadioKey\HubClient\Mqtt\Adapter\Bluerhinos\BluerhinosMqttCommandPublisher(
                getenv('MQTT_HOST'),
                getenv('MQTT_PORT'),
                getenv('MQTT_CLIENT_ID'),
                getenv('MQTT_USER'),
                getenv('MQTT_PASSWORD'),
            );
        }
    ),
    \Symfony\Component\Console\Application::class => factory(
        function (ContainerInterface $c) {
            $application = new \Symfony\Component\Console\Application();
            $application->setCommandLoader(
                new \Symfony\Component\Console\CommandLoader\ContainerCommandLoader(
                    $c,
                    [
                        'webhook:set' => \Radiokey\MessengerControl\Messenger\ConsoleCommand\WebhookSetCommand::class,
                    ]
                )
            );

            return $application;
        }
    ),
];