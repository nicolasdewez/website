<?php

namespace AppBundle\Service;

use AppBundle\Model\Contact;

/**
 * Class SendMail.
 */
class SendMail
{
    const CONTACT_TO = 'dewez.nicolas@gmail.com';
    const CONTACT_BODY = 'nicolas-dewez.com';

    /** @var \Swift_Mailer */
    private $mailer;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * @param \Swift_Mailer     $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param Contact $contact
     */
    public function sendContact(Contact $contact)
    {
        $message = \Swift_Message::newInstance()
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
