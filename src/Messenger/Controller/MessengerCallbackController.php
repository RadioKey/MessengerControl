<?php

declare(strict_types=1);

namespace Radiokey\MessengerControl\Messenger\Controller;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use RadioKey\HubClient\Command\SendCommand;
use RadioKey\HubClient\RadioKeyHubClient;
use Radiokey\MessengerControl\Messenger\Client\MessengerClientInterface;

class MessengerCallbackController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RadioKeyHubClient
     */
    private $hubClient;

    /**
     * @var MessengerClientInterface
     */
    private $messengerClient;

    /**
     * @param LoggerInterface $logger
     * @param RadioKeyHubClient $hubClient
     * @param MessengerClientInterface $messengerClient
     */
    public function __construct(
        LoggerInterface $logger,
        RadioKeyHubClient $hubClient,
        MessengerClientInterface $messengerClient
    ) {
        $this->logger = $logger;
        $this->hubClient = $hubClient;
        $this->messengerClient = $messengerClient;
    }

    public function handle(
        string $messenger,
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $requestBody = $request->getBody()->getContents();

        // debug
        $this->logger->debug(
            '[MessengerCallbackController] Request accepted',
            [
                'messenger' => $messenger,
                'request' => $requestBody,
            ]
        );

        // parse request
        try {
            // parse JSON
            $requestBody = \json_decode($requestBody, true, 512, \JSON_THROW_ON_ERROR);
            if (empty($requestBody)) {
                throw new \InvalidArgumentException('Invalid JSON');
            }
        } catch (\Throwable $e) {
            return $response->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        // build message
        try {
            // @todo: choose instance by $messenger
            $callbackMessage = $this->messengerClient->buildCallbackMessage($requestBody);

            if (empty($callbackMessage)) {
                // system message obtained
                return $response->withStatus(StatusCodeInterface::STATUS_OK);
            }
        } catch (\Throwable $e) {
            $this->logger->critical(
                '[MessengerCallbackController] Can not build callback message: ' . $e->getMessage(),
                [
                    'exception' => $e,
                ]
            );

            return $response->withStatus(StatusCodeInterface::STATUS_OK);
        }

        if ($callbackMessage->getText() === '/open') {
            try {
                // handle message
                $this->hubClient->publishCommand(
                    getenv('HUB_ADDRESS'),
                    new SendCommand(
                        1,
                        25,
                        315,
                        (int)getenv('HUB_SEND_CODE'),
                        24
                    )
                );

                try {
                    $this->messengerClient->sendMessage($callbackMessage->getChatId(), 'Command executed');
                } catch (\Throwable $e) {}
            } catch (\Throwable $e) {
                $this->messengerClient->sendMessage(
                    $callbackMessage->getChatId(),
                    'Command execution failed'
                );

                $this->logger->critical(
                    '[MessengerCallbackController] MQTT Publish error: ' . $e->getMessage(),
                    [
                        'exception' => $e,
                    ]
                );
            }
        }

        return $response->withStatus(StatusCodeInterface::STATUS_OK);
    }
}