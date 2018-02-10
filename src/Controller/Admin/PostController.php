<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
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
 * @Route("/posts")
 */
class PostController
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
     * @Route("", name="app_admin_post_list", methods={"GET"})
     */
    public function listAction(): Response
    {
        $elements = $this->manager->getRepository(Post::class)->findBy([], ['writingDate' => 'DESC']);

        return new Response(
            $this->twig->render('admin/post/list.html.twig', ['elements' => $elements])
        );
    }

    /**
     * @Route("/create", name="app_admin_post_create", methods={"POST", "GET"})
     */
    public function createAction(Request $request): Response
    {
        $post = new Post();
        $form = $this->formFactory->create(PostType::class, $post);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($post);
            $this->manager->flush();

            $this->session->getFlashBag()->add('notice', 'Post ajouté.');

            return new RedirectResponse($this->router->generate('app_admin_post_list'));
        }

        return new Response(
            $this->twig->render('admin/post/create.html.twig', ['form' => $form->createView()])
        );
    }

    /**
     * @Route("/{id}", name="app_admin_post_edit", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function editAction(Request $request, Post $post): Response
    {
        $form = $this->formFactory->create(PostType::class, $post);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            $this->session->getFlashBag()->add('notice', 'Post modifié.');

            return new RedirectResponse($this->router->generate('app_admin_post_list'));
        }

        return new Response(
            $this->twig->render('admin/post/edit.html.twig', ['form' => $form->createView()])
        );
    }

    /**
     * @Route("/{id}/preview", name="app_admin_post_preview", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function previewAction(Post $post): Response
    {
        return new Response(
            $this->twig->render('blog/post.html.twig', ['post' => $post])
        );
    }
}
