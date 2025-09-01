<?php

namespace App\Repository;

use App\Entity\PiggyBank;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PiggyBankRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PiggyBank::class);
    }

    public function findByChatId(string $chatId): ?PiggyBank
    {
        return $this->findOneBy(['chatId' => $chatId]);
    }

    public function createForChat(string $chatId): PiggyBank
    {
        $piggyBank = new PiggyBank();
        $piggyBank->setChatId($chatId);
        $piggyBank->setAmount(0.0);

        $this->getEntityManager()->persist($piggyBank);
        $this->getEntityManager()->flush();

        return $piggyBank;
    }
}
