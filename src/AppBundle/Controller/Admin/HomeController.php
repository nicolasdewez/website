<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Energy;
use AppBundle\Exception\ActualPasswordNotValid;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Model\ChangePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("", name="app_admin_home")
     *
     * @return Response
     */
    public function homeAction(): Response
    {
        return $this->render('admin/home/home.html.twig');
    }

    /**
     * @Route("/change-password", name="app_admin_change_password")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function changePasswordAction(Request $request): Response
    {
        $changePassword = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->get('app.user.change_password')->process($this->getUser(), $changePassword->getActual(), $changePassword->getNew());
                $this->addFlash('notice', 'Mot de passe modifié.');
            } catch (ActualPasswordNotValid $exception) {
                $this->addFlash('error', 'Le mot de passe n\'a pas pu être modifié. Mot de passe actuel non valide.');
            }

            return $this->redirectToRoute('app_admin_home');
        }

        return $this->render('admin/home/change-password.html.twig', ['form' => $form->createView()]);
    }
}
