<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Model\Contact;
use App\Mailer\SendMail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Twig;

/**
 * @Route("/contact")
 */
class ContactController
{
    /** @var Twig */
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("", name="app_contact")
     */
    public function indexAction(Request $request, FormFactoryInterface $formFactory, RouterInterface $router, SendMail $sendMail): Response
    {
        $contact = new Contact();
        $form = $formFactory->create(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sendMail->sendContact($contact);

            return new RedirectResponse($router->generate('app_contact.validation'));
        }

        return new Response(
            $this->twig->render(
                'contact/index.html.twig',
                ['form' => $form->createView()]
            )
        );
    }

    /**
     * @Route("/validation", name="app_contact.validation")
     */
    public function validAction(): Response
    {
        return new Response(
            $this->twig->render('contact/validation.html.twig')
        );
    }
}
