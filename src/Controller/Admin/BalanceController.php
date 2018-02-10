<?php

namespace App\Controller\Admin;

use App\Form\BalanceSearchType;
use App\Model\BalanceSearch;
use App\Service\BalanceStatistics;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

class BalanceController
{
    /**
     * @Route("/balance", name="app_admin_balance", methods={"GET", "POST"})
     */
    public function indexAction(
        Request $request,
        FormFactoryInterface $formFactory,
        BalanceStatistics $statistics,
        Twig  $twig
    ): Response {
        $search = new BalanceSearch();
        $form = $formFactory->create(BalanceSearchType::class, $search);
        $form->add('submit', SubmitType::class, ['label' => 'Chercher', 'attr' => ['class' => 'btn btn-success']]);
        $form->handleRequest($request);

        $data = $statistics->execute($search->getYear());

        return new Response(
            $twig->render('admin/balance/index.html.twig', [
                'form' => $form->createView(),
                'data' => $data,
                'year' => $search->getYear(),
            ])
        );
    }
}
