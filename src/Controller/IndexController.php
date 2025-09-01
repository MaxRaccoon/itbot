<?php
namespace App\Controller;

use App\Entity\Tests;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'main', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }
}
