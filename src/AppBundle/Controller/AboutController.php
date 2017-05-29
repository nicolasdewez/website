<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Energy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AboutController extends Controller
{
    /**
     * @Route("/about", name="app_about")
     */
    public function indexAction(): Response
    {
        return $this->render('about/index.html.twig');
    }
}
