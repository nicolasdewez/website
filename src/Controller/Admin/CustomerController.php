<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
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
 * @Route("/customers")
 */
class CustomerController
{
    /** @var EntityManagerInterface */
    private $manager;

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
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        SessionInterface $session,
        Twig $twig
    ) {
        $this->manager = $manager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->session = $session;
        $this->twig = $twig;
    }

    /**
     * @Route("", name="app_admin_customer_list", methods={"GET"})
     */
    public function listAction(): Response
    {
        $elements = $this->manager->getRepository(Customer::class)->findBy([], ['id' => 'ASC']);

        return new Response(
            $this->twig->render('admin/customer/list.html.twig', ['elements' => $elements])
        );
    }

    /**
     * @Route("/create", name="app_admin_customer_create", methods={"POST", "GET"})
     */
    public function createAction(Request $request): Response
    {
        $customer = new Customer();
        $form = $this->formFactory->create(CustomerType::class, $customer);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($customer);
            $this->manager->flush();

            $this->session->getFlashBag()->add('notice', 'Client ajouté.');

            return new RedirectResponse($this->router->generate('app_admin_customer_list'));
        }

        return new Response(
            $this->twig->render('admin/customer/create.html.twig', ['form' => $form->createView()])
        );
    }

    /**
     * @Route("/{id}", name="app_admin_customer_edit", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function editAction(Request $request, Customer $customer): Response
    {
        $form = $this->formFactory->create(CustomerType::class, $customer);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            $this->session->getFlashBag()->add('notice', 'Client modifié.');

            return new RedirectResponse($this->router->generate('app_admin_customer_list'));
        }

        return new Response(
            $this->twig->render('admin/customer/edit.html.twig', ['form' => $form->createView()])
        );
    }
}
