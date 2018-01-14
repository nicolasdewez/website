<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

class FreelanceController
{
    /**
     * @Route("/freelance", name="app_freelance")
     */
    public function indexAction(Twig $twig): Response
    {
        return new Response(
            $twig->render('freelance/index.html.twig')
        );
    }
}
