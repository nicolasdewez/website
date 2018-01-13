<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment as Twig;

class SecurityController
{
    /** @var Twig */
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/access-secured-area", name="app_login")
     */
    public function loginAction(AuthenticationUtils $helper, FormFactoryInterface $formFactory): Response
    {
        $form = $formFactory->create(LoginType::class);

        return new Response(
            $this->twig->render('security/login.html.twig', [
                'form' => $form->createView(),
                'error' => $helper->getLastAuthenticationError(),
            ])
        );
    }

    public function userBarAction(LoggerInterface $logger, UserInterface $user = null): Response
    {
        if (null === $user) {
            return new Response();
        }

        if (!($user instanceof User)) {
            $logger->error(sprintf(
                'User %s is not a valid user (User instance expected, %s found)',
                $user->getUsername(),
                get_class($user)
            ));

            return new Response();
        }

        return new Response(
            $this->twig->render('admin/common/user-bar.html.twig', [
                'username' => $user->getUsername(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
            ])
        );
    }
}
