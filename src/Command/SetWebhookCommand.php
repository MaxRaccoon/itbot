<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

class SetWebhookCommand extends Command
{
    protected static $defaultName = 'app:set-webhook';

    public function __construct()
    {
        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = getenv('TELEGRAM_BOT_TOKEN');
        $domain = getenv('APP_DOMAIN');
        $webhookUrl = 'https://' . $domain . '/telegram/webhook';

        $client = HttpClient::create();
        $response = $client->request('GET', "https://api.telegram.org/bot{$token}/setWebhook", [
            'query' => [
                'url' => $webhookUrl
            ]
        ]);

        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}
