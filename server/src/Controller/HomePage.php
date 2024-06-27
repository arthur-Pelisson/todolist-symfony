<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
class HomePage extends AbstractController {

    public function getHomePage(): Response {

        return $this->render('home.html.twig', [
        ]);
    }
}