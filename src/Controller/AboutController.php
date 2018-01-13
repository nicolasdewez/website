<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

class AboutController
{
    /**
     * @Route("/about", name="app_about")
     */
    public function indexAction(Twig $twig): Response
    {
        return new Response($twig->render('about/index.html.twig'));
    }
}
