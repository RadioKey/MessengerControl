<?php

declare(strict_types=1);

namespace Radiokey\MessengerControl\Messenger\Client\Exception;

/**
 * This bot is a server and can't handle request from client
 */
class MessengerServerRequestException extends MessengerClientException
{

}