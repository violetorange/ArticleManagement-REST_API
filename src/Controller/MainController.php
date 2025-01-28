<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', []);
    }

    #[Route('/articles/{articleId}', name: 'app_articles')]
    public function articleItem($articleId): Response
    {
        return $this->render('main/article-item.html.twig', ['articleId' => $articleId]);
    }
}
