<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Energy;
use AppBundle\Exception\ActualPasswordNotValid;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\VisitSearchType;
use AppBundle\Model\ChangePassword;
use AppBundle\Model\VisitSearch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class VisitController.
 *
 * @Route("/visits")
 */
class VisitController extends Controller
{
    /**
     * @Route("", name="app_admin_visit")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request): Response
    {
        $elements = [];
        $visitSearch = new VisitSearch();
        $form = $this->createForm(VisitSearchType::class, $visitSearch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $elements = $this->get('app.manager.visit')->searchVisit($visitSearch);
        }

        return $this->render('admin/visit/search.html.twig', [
            'form' => $form->createView(),
            'isSearched' => $form->isSubmitted(),
            'elements' => $elements,
        ]);
    }
}
