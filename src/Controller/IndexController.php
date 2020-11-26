<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

#[Route('/', name: 'app_')]
class IndexController extends AbstractController
{
    #[Route('', name: 'index')]
    public function login(): Response
    {
        return $this->render('app/index.html.twig');
    }
}
