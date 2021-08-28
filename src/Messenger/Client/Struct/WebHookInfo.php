<?php

declare(strict_types=1);

namespace Radiokey\MessengerControl\Messenger\Client\Struct;

/**
 * For telegram:
 * @see https://core.telegram.org/bots/api#webhookinfo
 */
class WebHookInfo
{
    /**
     * @var string|null
     */
    private $url;

    /**
     * @param string|null $url
     */
    public function __construct(?string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string|null when hook not set return null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}