<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController.
 *
 * @Route("/posts")
 */
class PostController extends Controller
{
    /**
     * @Route("", name="app_admin_post_list", methods={"GET"})
     *
     * @return Response
     */
    public function listAction(): Response
    {
        return $this->render('admin/post/list.html.twig', [
            'elements' => $this->get('doctrine.orm.entity_manager')->getRepository(Post::class)->findBy([], ['writingDate' => 'DESC']),
        ]);
    }

    /**
     * @Route("/create", name="app_admin_post_create", methods={"POST", "GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->add('submit', SubmitType::class, ['label' => 'submit', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->persist($post);
            $manager->flush();

            $this->addFlash('notice', 'Post ajouté.');

            return $this->redirectToRoute('app_admin_post_list');
        }

        return $this->render('admin/post/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}", name="app_admin_post_edit", requirements={"id": "\d+"}, methods={"GET", "POST"})
     *
     * @param Request    $request
     * @param Post $post
     *
     * @return Response
     */
    public function editAction(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->add('submit', SubmitType::class, ['label' => 'submit', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->flush();

            $this->addFlash('notice', 'Post modifié.');

            return $this->redirectToRoute('app_admin_post_list');
        }

        return $this->render('admin/post/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/preview", name="app_admin_post_preview", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param Post $post
     *
     * @return Response
     */
    public function previewAction(Post $post): Response
    {
        return $this->render('blog/post.html.twig', ['post' => $post]);
    }
}
