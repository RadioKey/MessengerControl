<?php

declare(strict_types=1);

namespace Radiokey\MessengerControl\Messenger\Client\Struct;

/**
 * For Telegram:
 * @see https://core.telegram.org/bots/api#message
 */
class CallbackMessage
{
    /**
     * Conversation the message belongs to
     *
     * @var string
     */
    private $chatId;

    /**
     * Sender, empty for messages sent to channels
     *
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $text;

    /**
     * @param string $chatId
     * @param string $userId
     * @param string $userName
     * @param string $text
     */
    public function __construct(string $chatId, string $userId, string $userName, string $text)
    {
        $this->chatId = $chatId;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}