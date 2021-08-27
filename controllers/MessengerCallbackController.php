<?php

declare(strict_types=1);

namespace Radiokey\MessengerControl\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MessengerCallbackController
{
    public function handle(
        string $messenger,
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $response->getBody()->write($messenger);

        return $response;
    }
}