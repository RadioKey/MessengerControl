<?php

declare(strict_types=1);

namespace Radiokey\MessengerControl\Messenger\ConsoleCommand;

use Radiokey\MessengerControl\Messenger\Client\MessengerClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebhookSetCommand extends Command
{
    /**
     * @var MessengerClientInterface
     */
    private $messengerClient;

    /**
     * @param MessengerClientInterface $messengerClient
     */
    public function __construct(MessengerClientInterface $messengerClient)
    {
        parent::__construct('webhook:set');

        $this->messengerClient = $messengerClient;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            // @todo: allow configure different messengers
            $webHookUrl = rtrim(getenv('APP_CANONICAL_URI'), '/') . '/messenger/callback/telegram';

            // check if webhook configured
            $webHookInfo = $this->messengerClient->getWebHookInfo();
            if ($webHookInfo->getUrl() === null) {
                // set web hook
                $output->writeln(
                    sprintf('<info>Setting web hook to %s</info>', $webHookUrl),
                    OutputInterface::VERBOSITY_VERBOSE
                );

                $this->messengerClient->setWebhook($webHookUrl);
            } else if ($webHookInfo->getUrl() !== $webHookUrl) {
                $output->writeln(
                    sprintf(
                        '<info>Web hook already set to %s, replace with %s</info>',
                        $webHookInfo->getUrl(),
                        $webHookUrl
                    ),
                    OutputInterface::VERBOSITY_VERBOSE
                );

                $this->messengerClient->setWebhook($webHookUrl);
            } else {
                $output->writeln(
                    '<info>Valid webhook already set</info>',
                    OutputInterface::VERBOSITY_VERBOSE
                );
            }
        } catch (\Throwable $e) {
            $output->writeln(
                sprintf(
                    '<error>Can not set webhook</error>: %s',
                    $e->getMessage()
                )
            );

            return 1;
        }

        return 0;
    }
}