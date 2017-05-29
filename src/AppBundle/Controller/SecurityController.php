<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @Route("/access-secured-area", name="app_login")
     */
    public function loginAction(): Response
    {
        $helper = $this->get('security.authentication_utils');
        $form = $this->createForm(LoginType::class);

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @return Response
     */
    public function userBarAction(): Response
    {
        if (!$this->getUser()) {
            return new Response();
        }

        return $this->render('admin/common/user-bar.html.twig', [
            'username' => $this->getUser()->getUsername(),
            'firstName' => $this->getUser()->getFirstName(),
            'lastName' => $this->getUser()->getLastName(),
        ]);
    }
}
