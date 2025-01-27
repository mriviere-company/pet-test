<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PetController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('pet/index.html.twig');
    }
}
