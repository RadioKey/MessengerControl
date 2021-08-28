<?php

declare(strict_types=1);

namespace Radiokey\MessengerControl\Messenger\Client\Exception;

/**
 * This bot is a client and get error response from api server
 */
class MessengerApiResponseException extends MessengerClientException
{
    public function __construct(string $description)
    {
        parent::__construct($description);
    }
}