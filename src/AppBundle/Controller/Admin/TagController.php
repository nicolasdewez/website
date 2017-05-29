<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TagController.
 *
 * @Route("/tags")
 */
class TagController extends Controller
{
    /**
     * @Route("", name="app_admin_tag_list", methods={"GET"})
     *
     * @return Response
     */
    public function listAction(): Response
    {
        return $this->render('admin/tag/list.html.twig', [
            'elements' => $this->get('doctrine.orm.entity_manager')->getRepository(Tag::class)->findBy([], ['title' => 'ASC']),
        ]);
    }

    /**
     * @Route("/create", name="app_admin_tag_create", methods={"POST", "GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->add('submit', SubmitType::class, ['label' => 'submit', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->persist($tag);
            $manager->flush();

            $this->addFlash('notice', 'Tag ajouté.');

            return $this->redirectToRoute('app_admin_tag_list');
        }

        return $this->render('admin/tag/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}", name="app_admin_tag_edit", requirements={"id": "\d+"}, methods={"GET", "POST"})
     *
     * @param Request    $request
     * @param Tag $tag
     *
     * @return Response
     */
    public function editAction(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->add('submit', SubmitType::class, ['label' => 'submit', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->flush();

            $this->addFlash('notice', 'Tag modifié.');

            return $this->redirectToRoute('app_admin_tag_list');
        }

        return $this->render('admin/tag/edit.html.twig', ['form' => $form->createView()]);
    }
}
