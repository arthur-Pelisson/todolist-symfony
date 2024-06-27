<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class LuckyController extends AbstractController {


    public function randomNumber(int $max): Response
    {
        $number = random_int(0, $max);

        return $this->render('homefragment/_randomnumber.html.twig', [
            'random_number' => $number,
        ]);
    }
}