<?php

namespace AppBundle\EventListener;

use AppBundle\Service\VisitManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class VisitListener.
 */
class VisitListener
{
    /** @var VisitManager */
    private $manager;

    /**
     * @param VisitManager $manager
     */
    public function __construct(VisitManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        $pathInfo = $event->getRequest()->getPathInfo();
        if (preg_match('#/(access-)?secured-area#', $pathInfo)) {
            return;
        }

        $this->manager->addVisit((new \DateTime())->setTime(0, 0), $pathInfo);
    }
}