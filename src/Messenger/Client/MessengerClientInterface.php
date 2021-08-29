<?php

declare(strict_types=1);

namespace Radiokey\MessengerControl\Messenger\Client;

use Radiokey\MessengerControl\Messenger\Client\Exception\MessengerApiRequestException;
use Radiokey\MessengerControl\Messenger\Client\Exception\MessengerApiResponseException;
use Radiokey\MessengerControl\Messenger\Client\Struct\CallbackMessage;
use Radiokey\MessengerControl\Messenger\Client\Struct\WebHookInfo;

interface MessengerClientInterface
{
    /**
     * @param string $url
     *
     * For Telegram:
     * @see https://core.telegram.org/bots/api#setwebhook
     *
     * @throws MessengerApiRequestException
     * @throws MessengerApiResponseException
     */
    public function setWebHook(string $url): void;

    /**
     * Use this method to remove webhook integration if you decide to
     * switch back to getUpdates. Returns True on success.
     *
     * For Telegram:
     * @see https://core.telegram.org/bots/api#deletewebhook
     *
     * @throws MessengerApiRequestException
     * @throws MessengerApiResponseException
     */
    public function deleteWebHook(): void;

    /**
     * For Telegram:
     * @see https://core.telegram.org/bots/api#getwebhookinfo
     *
     * @return WebHookInfo
     *
     * @throws MessengerApiRequestException
     * @throws MessengerApiResponseException
     */
    public function getWebHookInfo(): WebhookInfo;

    /**
     * For Telegram:
     * @see https://core.telegram.org/bots/webhooks#testing-your-bot-with-updates Examples
     * @see https://core.telegram.org/bots/api#update Specification
     *
     * @param array $updateData
     *
     * @return CallbackMessage
     *
     * @throws MessengerApiRequestException
     */
    public function buildCallbackMessage(array $updateData): ?CallbackMessage;

    /**
     * For Telegram:
     * @link https://core.telegram.org/bots/api#sendmessage
     *
     * @param string $chatId Unique identifier for the target chat or username of the target channel (in the format @channelusername)
     * @param string $text Text of the message to be sent
     */
    public function sendMessage(string $chatId, string $text): void;
}