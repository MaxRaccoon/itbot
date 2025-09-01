<?php

namespace App\Controller;

use App\Service\TelegramBotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;

class TelegramBotController extends AbstractController
{
    private TelegramBotService $telegramBotService;
    private ChatterInterface $chatter;

    public function __construct(TelegramBotService $telegramBotService, ChatterInterface $chatter)
    {
        $this->telegramBotService = $telegramBotService;
        $this->chatter = $chatter;
    }

    #[Route('/telegram/webhook', name: 'telegram_webhook', methods: ['POST'])]
    public function webhook(Request $request): Response
    {
        $content = json_decode($request->getContent(), true);

        // Проверяем, что это сообщение от пользователя
        if (isset($content['message'])) {
            $message = $content['message'];
            $chatId = $message['chat']['id'];

            // Обрабатываем сообщение
            $responseText = $this->telegramBotService->handleMessage($message);

            // Отправляем ответ
            $this->sendMessage($chatId, $responseText);
        }

        return new JsonResponse(['status' => 'ok']);
    }

    private function sendMessage(int $chatId, string $text): void
    {
        $chatMessage = new ChatMessage($text);
        $telegramOptions = (new TelegramOptions())
            ->chatId($chatId)
            ->parseMode('HTML');

        $chatMessage->options($telegramOptions);
        $this->chatter->send($chatMessage);
    }
}
