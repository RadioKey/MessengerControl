<?php

declare(strict_types=1);

namespace Radiokey\MessengerControl\Messenger\ClientAdapter\Telegram\Longman;

use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\Update as LongmanTelegramBotUpdate;
use Longman\TelegramBot\Exception\TelegramException as LongmanTelegramBotException;
use Radiokey\MessengerControl\Messenger\Client\Exception\MessengerApiRequestException;
use Radiokey\MessengerControl\Messenger\Client\Exception\MessengerApiResponseException;
use Radiokey\MessengerControl\Messenger\Client\Exception\MessengerServerRequestException;
use Radiokey\MessengerControl\Messenger\Client\MessengerClientInterface;
use Radiokey\MessengerControl\Messenger\Client\Struct\CallbackMessage;
use Radiokey\MessengerControl\Messenger\Client\Struct\WebHookInfo;

/**
 * Adapter to Longman's Telegram API client
 *
 * @see https://github.com/php-telegram-bot
 */
class LongmanTelegramBotClient implements MessengerClientInterface
{
    /**
     * @var Telegram
     */
    private $telegramClient;

    /**
     * @param Telegram $telegramClient
     */
    public function __construct(Telegram $telegramClient)
    {
        $this->telegramClient = $telegramClient;
    }

    /**
     * @param string $url
     *
     * @throws MessengerApiRequestException
     * @throws MessengerApiResponseException
     */
    public function setWebHook(string $url): void
    {
        try {
            $response = Request::setWebhook([
                'url' => $url,
            ]);
        } catch (\Throwable $e) {
            throw new MessengerApiRequestException(
                'Request error: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        if (!$response->isOk()) {
            throw new MessengerApiResponseException($response->getDescription());
        }
    }

    /**
     * @throws MessengerApiRequestException
     * @throws MessengerApiResponseException
     */
    public function deleteWebHook(): void
    {
        try {
            $response = Request::deleteWebhook();
        } catch (\Throwable $e) {
            throw new MessengerApiRequestException($e->getMessage(), $e->getCode(), $e);
        }

        if (!$response->isOk()) {
            throw new MessengerApiResponseException($response->getDescription());
        }
    }

    /**
     * @return WebHookInfo
     *
     * @throws MessengerApiRequestException
     * @throws MessengerApiResponseException
     */
    public function getWebHookInfo(): WebHookInfo
    {
        try {
            $response = Request::getWebhookInfo();
        } catch (\Throwable $e) {
            throw new MessengerApiRequestException($e->getMessage(), $e->getCode(), $e);
        }

        if (!$response->isOk()) {
            throw new MessengerApiResponseException($response->getDescription());
        }

        /** @var \Longman\TelegramBot\Entities\WebhookInfo $result */
        $result = $response->getResult();

        $webHookInfo = new WebHookInfo(
            $result->getUrl() ? $result->getUrl() : null
        );

        return $webHookInfo;
    }

    /**
     * @param array $updateData
     *
     * @return CallbackMessage
     *
     * @throws MessengerServerRequestException
     */
    public function buildCallbackMessage(array $updateData): ?CallbackMessage
    {
        try {
            $update = new LongmanTelegramBotUpdate($updateData);
        } catch (LongmanTelegramBotException $e) {
            throw new MessengerServerRequestException('Can not create Update object');
        }

        $message = $update->getUpdateContent();

        if ($message instanceof Message) {
            return new CallbackMessage(
                (string) $message->getChat()->getId(),
                $message->getFrom() ? (string) $message->getFrom()->getId() : null,
                $message->getFrom() ? $message->getFrom()->getFirstName() : null,
                $message->getText()
            );
        } else {
            return null;
        }
    }

    /**
     * @param string $chatId
     * @param string $text
     *
     * @return CallbackMessage
     *
     * @throws MessengerApiRequestException
     * @throws MessengerApiResponseException
     */
    public function sendMessage(string $chatId, string $text): CallbackMessage
    {
        try {
            $response = Request::sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => null,
            ]);
        } catch (\Throwable $e) {
            throw new MessengerApiRequestException($e->getMessage(), $e->getCode(), $e);
        }

        if (!$response->isOk()) {
            throw new MessengerApiResponseException($response->getDescription());
        }

        /** @var \Longman\TelegramBot\Entities\Message $message */
        $message = $response->getResult();

        return new CallbackMessage(
            (string) $message->getChat()->getId(),
            (string) $message->getFrom()->getId(),
            $message->getFrom()->getFirstName(),
            $message->getText()
        );
    }
}