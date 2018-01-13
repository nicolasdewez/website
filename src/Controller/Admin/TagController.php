<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagType;
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
 * @Route("/tags")
 */
class TagController
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
     * @Route("", name="app_admin_tag_list", methods={"GET"})
     */
    public function listAction(): Response
    {
        $elements = $this->manager->getRepository(Tag::class)->findBy([], ['title' => 'ASC']);

        return new Response(
            $this->twig->render('admin/tag/list.html.twig', ['elements' => $elements])
        );
    }

    /**
     * @Route("/create", name="app_admin_tag_create", methods={"POST", "GET"})
     */
    public function createAction(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->formFactory->create(TagType::class, $tag);
        $form->add('submit', SubmitType::class, ['label' => 'submit', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($tag);
            $this->manager->flush();

            $this->session->getFlashBag()->add('notice', 'Tag ajouté.');

            return new RedirectResponse($this->router->generate('app_admin_tag_list'));
        }

        return new Response(
            $this->twig->render('admin/tag/create.html.twig', ['form' => $form->createView()])
        );
    }

    /**
     * @Route("/{id}", name="app_admin_tag_edit", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function editAction(Request $request, Tag $tag): Response
    {
        $form = $this->formFactory->create(TagType::class, $tag);
        $form->add('submit', SubmitType::class, ['label' => 'submit', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            $this->session->getFlashBag()->add('notice', 'Tag modifié.');

            return new RedirectResponse($this->router->generate('app_admin_tag_list'));
        }

        return new Response(
            $this->twig->render('admin/tag/edit.html.twig', ['form' => $form->createView()])
        );
    }
}
