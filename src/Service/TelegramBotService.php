<?php

namespace App\Service;

use App\Entity\PiggyBank;
use App\Repository\PiggyBankRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TelegramBotService
{
    private PiggyBankRepository $piggyBankRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        PiggyBankRepository $piggyBankRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->piggyBankRepository = $piggyBankRepository;
        $this->entityManager = $entityManager;
    }

    public function handleMessage(array $message): ?string
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';
        if (!$this->isBotCommand($text)) {
            return null;
        }

        // Получаем или создаем копилку для чата
        $piggyBank = $this->piggyBankRepository->findByChatId($chatId);
        if (!$piggyBank) {
            $piggyBank = $this->piggyBankRepository->createForChat($chatId);
        }

        // Обрабатываем команды
        if (preg_match('/добавь\s+(\d+)(р|руб|рублей)?/iu', $text, $matches)) {
            return $this->handleAdd($piggyBank, (float)$matches[1]);
        }

        if (preg_match('/(отними|возьми с копилки)\s+(\d+)(р|руб|рублей)?/iu', $text, $matches)) {
            return $this->handleSubtract($piggyBank, (float)$matches[2]);
        }

        if (preg_match('/покажи\s+счёт/iu', $text)) {
            return $this->handleShow($piggyBank);
        }

        return "Не понимаю команду. Доступные команды:\n"
            . "• добавь [сумма]р - добавить деньги\n"
            . "• отними [сумма]р - взять деньги\n"
            . "• покажи счёт - показать текущий баланс";
    }

    private function isBotCommand(string $text): bool
    {
        return preg_match('/^(\/|@OfficePiggyBankBot)/', $text) === 1;
    }

    private function handleAdd(PiggyBank $piggyBank, float $amount): string
    {
        $piggyBank->addAmount($amount);
        $this->entityManager->flush();

        return "✅ Добавлено {$amount}р. Текущий счёт: {$piggyBank->getAmount()}р";
    }

    private function handleSubtract(PiggyBank $piggyBank, float $amount): string
    {
        if ($piggyBank->getAmount() < $amount) {
            return "❌ Недостаточно средств. Текущий счёт: {$piggyBank->getAmount()}р";
        }

        $piggyBank->subtractAmount($amount);
        $this->entityManager->flush();

        return "✅ Вычтено {$amount}р. Текущий счёт: {$piggyBank->getAmount()}р";
    }

    private function handleShow(PiggyBank $piggyBank): string
    {
        return "💰 Текущий счёт копилки: {$piggyBank->getAmount()}р";
    }
}
