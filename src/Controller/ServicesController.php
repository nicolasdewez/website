<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

class ServicesController
{
    /**
     * @Route("/services", name="app_services")
     */
    public function indexAction(Twig $twig): Response
    {
        return new Response(
            $twig->render('services/index.html.twig')
        );
    }
}
