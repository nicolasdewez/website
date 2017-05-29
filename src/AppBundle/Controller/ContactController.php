<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use AppBundle\Model\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("", name="app_contact")
     */
    public function indexAction(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.send_mail')->sendContact($contact);

            return $this->redirectToRoute('app_contact.validation');
        }

        return $this->render('contact/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @return Response
     *
     * @Route("/validation", name="app_contact.validation")
     */
    public function validAction(): Response
    {
        return $this->render('contact/validation.html.twig');
    }
}
