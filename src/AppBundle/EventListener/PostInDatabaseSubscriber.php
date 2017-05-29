<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Post;
use AppBundle\Service\DefineSlug;
use AppBundle\Service\GetAndSetBodyInPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PostInDatabaseSubscriber implements EventSubscriber
{
    /** @var GetAndSetBodyInPost */
    private $getAndSetBodyInPost;

    /** @var DefineSlug */
    private $defineSlug;

    /**
     * @param GetAndSetBodyInPost $getAndSetBodyInPost
     * @param DefineSlug          $defineSlug
     */
    public function __construct(GetAndSetBodyInPost $getAndSetBodyInPost, DefineSlug $defineSlug)
    {
        $this->getAndSetBodyInPost = $getAndSetBodyInPost;
        $this->defineSlug = $defineSlug;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        if (!$this->supports($args->getEntity())) {
            return;
        }

        $entity = $args->getEntity();
        $entity->setBody($this->getAndSetBodyInPost->get($entity->getId()));
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->saveSlug($args);
        $this->saveUpdatedDate($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->saveBody($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->saveSlug($args);
        $this->saveUpdatedDate($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->saveBody($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    private function saveSlug(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$this->supports($entity)) {
            return;
        }

        $entity->setSlug($this->defineSlug->build($entity->getTitle()));
    }

    /**
     * @param LifecycleEventArgs $args
     */
    private function saveBody(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$this->supports($entity)) {
            return;
        }

        $this->getAndSetBodyInPost->set($entity->getId(), $entity->getBody());
    }

    /**
     * @param LifecycleEventArgs $args
     */
    private function saveUpdatedDate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$this->supports($entity)) {
            return;
        }

        $entity->setUpdateDate();
    }

    /**
     * @param object $entity
     *
     * @return bool
     */
    private function supports($entity): bool
    {
        return $entity instanceof Post;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return ['postLoad', 'postPersist', 'postUpdate', 'prePersist', 'preUpdate'];
    }
}