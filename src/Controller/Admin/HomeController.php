<?php

namespace App\Controller\Admin;

use App\Exception\ActualPasswordNotValid;
use App\Form\ChangePasswordType;
use App\Model\ChangePassword;
use App\Security\ChangePasswordUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment as Twig;

class HomeController
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var Session */
    private $session;

    /** @var Twig */
    private $twig;

    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, SessionInterface $session, Twig $twig)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->session = $session;
        $this->twig = $twig;
    }

    /**
     * @Route("", name="app_admin_home")
     */
    public function homeAction(): Response
    {
        return new Response(
            $this->twig->render('admin/home/home.html.twig')
        );
    }

    /**
     * @Route("/change-password", name="app_admin_change_password")
     */
    public function changePasswordAction(Request $request, ChangePasswordUser $changePasswordUser, UserInterface $user): Response
    {
        $changePassword = new ChangePassword();
        $form = $this->formFactory->create(ChangePasswordType::class, $changePassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $changePasswordUser->process($user, $changePassword->getActual(), $changePassword->getNew());
                $this->session->getFlashBag()->add('notice', 'Mot de passe modifié.');
            } catch (ActualPasswordNotValid $exception) {
                $this->session->getFlashBag()->add('error', 'Le mot de passe n\'a pas pu être modifié. Mot de passe actuel non valide.');
            }

            return new RedirectResponse($this->router->generate('app_admin_home'));
        }

        return new Response(
            $this->twig->render('admin/home/change-password.html.twig', ['form' => $form->createView()])
        );
    }
}
