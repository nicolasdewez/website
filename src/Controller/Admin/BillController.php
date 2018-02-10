<?php

namespace App\Controller\Admin;

use App\Creator\BillIntoPdfCreator;
use App\Entity\Bill;
use App\Exception\GoogleSaverException;
use App\Form\BillType;
use App\Saver\GoogleSaver;
use App\Workflow\BillWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Twig;

/**
 * @Route("/bills")
 */
class BillController
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var BillWorkflow */
    private $workflow;

    /** @var BillIntoPdfCreator */
    private $pdfCreator;

    /** @var GoogleSaver */
    private $googleSaver;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var Session */
    private $session;

    /** @var Twig */
    private $twig;

    public function __construct(
        EntityManagerInterface $manager,
        BillWorkflow $workflow,
        BillIntoPdfCreator $pdfCreator,
        GoogleSaver $googleSaver,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        SessionInterface $session,
        Twig $twig
    ) {
        $this->manager = $manager;
        $this->workflow = $workflow;
        $this->pdfCreator = $pdfCreator;
        $this->googleSaver = $googleSaver;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->session = $session;
        $this->twig = $twig;
    }

    /**
     * @Route("", name="app_admin_bill_list", methods={"GET"})
     */
    public function listAction(): Response
    {
        $elements = $this->manager->getRepository(Bill::class)->findBy([], ['id' => 'ASC']);

        return new Response(
            $this->twig->render('admin/bill/list.html.twig', ['elements' => $elements])
        );
    }

    /**
     * @Route("/create", name="app_admin_bill_create", methods={"POST", "GET"})
     */
    public function createAction(Request $request): Response
    {
        $bill = new Bill();
        $form = $this->formFactory->create(BillType::class, $bill);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($bill);
            $this->manager->flush();

            $this->session->getFlashBag()->add('notice', 'Facture ajoutée.');
            $this->saveAction($bill);

            return new RedirectResponse($this->router->generate('app_admin_bill_list'));
        }

        return new Response(
            $this->twig->render('admin/bill/create.html.twig', ['form' => $form->createView()])
        );
    }

    /**
     * @Route("/{id}", name="app_admin_bill_edit", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function editAction(Request $request, Bill $bill): Response
    {
        $form = $this->formFactory->create(BillType::class, $bill);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->workflow->applyEdit($bill);

            $this->session->getFlashBag()->add('notice', 'Facture modifiée.');
            $this->saveAction($bill);

            return new RedirectResponse($this->router->generate('app_admin_bill_list'));
        }

        return new Response(
            $this->twig->render('admin/bill/edit.html.twig', ['form' => $form->createView()])
        );
    }

    /**
     * @Route("/{id}/display", name="app_admin_bill_display", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function displayAction(Bill $bill, BillIntoPdfCreator $creator): Response
    {
        $path = $creator->execute($bill);

        $response = new PdfResponse(
            file_get_contents($path),
            sprintf('%s.pdf', $bill->getCode())
        );

        $creator->clean($path);

        return $response;
    }

    /**
     * @Route("/{id}/acquit", name="app_admin_bill_acquit", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function acquitAction(Bill $bill): Response
    {
        $this->workflow->applyAcquit($bill);
        $this->session->getFlashBag()->add('notice', 'Facture acquitée.');

        $this->saveAction($bill);

        return new RedirectResponse($this->router->generate('app_admin_bill_list'));
    }

    /**
     * @Route("/{id}/cancel", name="app_admin_bill_cancel", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function cancelAction(Bill $bill): Response
    {
        $this->workflow->applyCancel($bill);
        $this->session->getFlashBag()->add('notice', 'Facture annulée.');

        $this->saveAction($bill);

        return new RedirectResponse($this->router->generate('app_admin_bill_list'));
    }

    public function saveAction(Bill $bill): void
    {
        $path = $this->pdfCreator->execute($bill);
        try {
            $this->googleSaver->execute($path, GoogleSaver::FILE_TYPE_BILL);
            $this->session->getFlashBag()->add('notice', 'Facture sauvegardée.');
        } catch (GoogleSaverException $exception) {
            $this->session->getFlashBag()->add('warning', 'Le facture n\'a pas été sauvegardée.');
        }

        $this->pdfCreator->clean($path);
    }
}
