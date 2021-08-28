<?php

declare(strict_types=1);

namespace Radiokey\MessengerControl\Controller;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class MessengerCallbackController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(
        string $messenger,
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $response->getBody()->write($messenger);

        $requestBody = $request->getBody()->getContents();

        // debug
        $this->logger->debug(
            '[MessengerCallbackController] Request accepted',
            [
                'messenger' => $messenger,
                'request' => $requestBody,
            ]
        );

        // handle request
        try {
            // parse JSON
            $requestBody = \json_decode($requestBody, true, 512, \JSON_THROW_ON_ERROR);
            if (empty($requestBody)) {
                throw new \RuntimeException('Invalid JSON');
            }

            // find messenger handler

            // handle message

            $response->withStatus(StatusCodeInterface::STATUS_OK);
        } catch (\Throwable $e) {
            $this->logger->critical(
                '[MessengerCallbackController] ' . $e->getMessage(),
                [
                    'exception' => $e,
                ]
            );

            $response->withStatus(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}