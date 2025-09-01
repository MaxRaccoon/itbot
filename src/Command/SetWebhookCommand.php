<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;

class SetWebhookCommand extends Command
{
    protected static $defaultName = 'app:set-webhook';
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $this->params->get('telegram_token');
        $domain = $this->params->get('main_domain');
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
