<?php

namespace App\Controller\Admin;

use App\Creator\EstimateIntoPdfCreator;
use App\Entity\Estimate;
use App\Exception\GoogleSaverException;
use App\Form\EstimateType;
use App\Saver\GoogleSaver;
use App\Service\EstimateToBill;
use App\Workflow\EstimateWorkflow;
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
 * @Route("/estimates")
 */
class EstimateController
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var EstimateWorkflow */
    private $workflow;

    /** @var EstimateIntoPdfCreator */
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
        EstimateWorkflow $workflow,
        EstimateIntoPdfCreator $pdfCreator,
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
     * @Route("", name="app_admin_estimate_list", methods={"GET"})
     */
    public function listAction(): Response
    {
        $elements = $this->manager->getRepository(Estimate::class)->findBy([], ['id' => 'ASC']);

        return new Response(
            $this->twig->render('admin/estimate/list.html.twig', ['elements' => $elements])
        );
    }

    /**
     * @Route("/create", name="app_admin_estimate_create", methods={"POST", "GET"})
     */
    public function createAction(Request $request): Response
    {
        $estimate = new Estimate();
        $form = $this->formFactory->create(EstimateType::class, $estimate);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($estimate);
            $this->manager->flush();

            $this->session->getFlashBag()->add('notice', 'Devis ajouté.');
            $this->saveAction($estimate);

            return new RedirectResponse($this->router->generate('app_admin_estimate_list'));
        }

        return new Response(
            $this->twig->render('admin/estimate/create.html.twig', ['form' => $form->createView()])
        );
    }

    /**
     * @Route("/{id}", name="app_admin_estimate_edit", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function editAction(Request $request, Estimate $estimate): Response
    {
        $form = $this->formFactory->create(EstimateType::class, $estimate);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->workflow->applyEdit($estimate);

            $this->session->getFlashBag()->add('notice', 'Devis modifié.');
            $this->saveAction($estimate);

            return new RedirectResponse($this->router->generate('app_admin_estimate_list'));
        }

        return new Response(
            $this->twig->render('admin/estimate/edit.html.twig', ['form' => $form->createView()])
        );
    }

    /**
     * @Route("/{id}/display", name="app_admin_estimate_display", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function displayAction(Estimate $estimate, EstimateIntoPdfCreator $creator): Response
    {
        $path = $creator->execute($estimate);

        $response = new PdfResponse(
            file_get_contents($path),
            sprintf('%s.pdf', $estimate->getCode())
        );

        $creator->clean($path);

        return $response;
    }

    /**
     * @Route("/{id}/accept", name="app_admin_estimate_accept", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function acceptAction(Estimate $estimate): Response
    {
        $this->workflow->applyAccept($estimate);
        $this->session->getFlashBag()->add('notice', 'Devis accepté.');

        $this->saveAction($estimate);

        return new RedirectResponse($this->router->generate('app_admin_estimate_list'));
    }

    /**
     * @Route("/{id}/cancel", name="app_admin_estimate_cancel", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function cancelAction(Estimate $estimate): Response
    {
        $this->workflow->applyCancel($estimate);
        $this->session->getFlashBag()->add('notice', 'Devis annulé.');

        $this->saveAction($estimate);

        return new RedirectResponse($this->router->generate('app_admin_estimate_list'));
    }

    /**
     * @Route("/{id}/bill", name="app_admin_estimate_bill", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function createBillAction(Estimate $estimate, EstimateToBill $estimateToBill): Response
    {
        $bill = $estimateToBill->execute($estimate);
        $this->session->getFlashBag()->add('notice', 'Facture créée.');

        return new RedirectResponse($this->router->generate('app_admin_bill_edit', ['id' => $bill->getId()]));
    }

    public function saveAction(Estimate $estimate): void
    {
        $path = $this->pdfCreator->execute($estimate);
        try {
            $this->googleSaver->execute($path, GoogleSaver::FILE_TYPE_ESTIMATE);
            $this->session->getFlashBag()->add('notice', 'Devis sauvegardé.');
        } catch (GoogleSaverException $exception) {
            $this->session->getFlashBag()->add('warning', 'Le devis n\'a pas été sauvegardé.');
        }

        $this->pdfCreator->clean($path);
    }
}
