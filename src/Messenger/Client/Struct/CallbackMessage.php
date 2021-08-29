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
     * @var string|null
     */
    private $userId;

    /**
     * @var string|null
     */
    private $userName;

    /**
     * @var string
     */
    private $text;

    /**
     * @param string $chatId
     * @param string|null $userId
     * @param string|null $userName
     * @param string $text
     */
    public function __construct(string $chatId, ?string $userId, ?string $userName, string $text)
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
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
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