<?php

namespace App\Mailer;

use App\Model\Contact;

class SendMail
{
    const CONTACT_TO = 'dewez.nicolas@gmail.com';
    const CONTACT_BODY = 'nicolas-dewez.com';

    /** @var \Swift_Mailer */
    private $mailer;

    /** @var \Twig_Environment */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendContact(Contact $contact): void
    {
        $message = (new \Swift_Message())
            ->setTo(self::CONTACT_TO)
            ->setSubject(sprintf('%s - %s', self::CONTACT_BODY, $contact->getObject()))
            ->setFrom($contact->getEmail())
            ->setBody(
                $this->twig->render('contact/message.html.twig', [
                    'firstName' => $contact->getFirstName(),
                    'lastName' => $contact->getLastName(),
                    'message' => $contact->getMessage(),
                ]),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }
}
