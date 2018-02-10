<?php

namespace App\EventListener;

use App\Entity\Post;
use App\Generator\DefineSlugGenerator;
use App\Service\GetAndSetBodyInPost;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class PostListener
{
    /** @var GetAndSetBodyInPost */
    private $getAndSetBodyInPost;

    /** @var DefineSlugGenerator */
    private $defineSlug;

    public function __construct(GetAndSetBodyInPost $getAndSetBodyInPost, DefineSlugGenerator $defineSlug)
    {
        $this->getAndSetBodyInPost = $getAndSetBodyInPost;
        $this->defineSlug = $defineSlug;
    }

    public function postLoad(Post $post, LifecycleEventArgs $args): void
    {
        $post->setBody($this->getAndSetBodyInPost->get($post->getId()));
    }

    public function prePersist(Post $post, LifecycleEventArgs $args): void
    {
        $this->saveSlug($post);
        $this->saveUpdatedDate($post);
    }

    public function postPersist(Post $post, LifecycleEventArgs $args): void
    {
        $this->saveBody($post);
    }

    public function preUpdate(Post $post, PreUpdateEventArgs $args): void
    {
        $this->saveSlug($post);
        $this->saveUpdatedDate($post);
    }

    public function postUpdate(Post $post, LifecycleEventArgs $args): void
    {
        $this->saveBody($post);
    }

    private function saveSlug(Post $post): void
    {
        $post->setSlug($this->defineSlug->build($post->getTitle()));
    }

    private function saveBody(Post $post): void
    {
        $this->getAndSetBodyInPost->set($post->getId(), $post->getBody());
    }

    private function saveUpdatedDate(Post $post): void
    {
        $post->setUpdateDate();
    }
}
