<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$app = \DI\Bridge\Slim\Bridge::create();

$app->get(
    '/messenger/callback/{messenger}',
    function (Request $request, Response $response, array $args) {
        $name = $args['messenger'];
        $response->getBody()->write("Hello, $name");

        return $response;
    }
);

$app->run();