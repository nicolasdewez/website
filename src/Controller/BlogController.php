<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Tag;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment as Twig;

class BlogController
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var Twig */
    private $twig;

    public function __construct(EntityManagerInterface $manager, Twig $twig)
    {
        $this->manager = $manager;
        $this->twig = $twig;
    }

    /**
     * @Route("/{page}", name="app_blog", methods={"GET"}, defaults={"page": 1}, requirements={"page": "^\d+$"})
     */
    public function homeAction(int $page): Response
    {
        /** @var PostRepository $repository */
        $repository = $this->manager->getRepository(Post::class);

        $nbPages = (int) ceil($repository->countVisiblePost() / Post::NB_PER_PAGE);
        if ($page > $nbPages) {
            throw new NotFoundHttpException();
        }

        $posts = $repository->findVisiblePost(Post::NB_PER_PAGE * ($page - 1), Post::NB_PER_PAGE);

        return new Response(
            $this->twig->render(
                'blog/home.html.twig',
                ['posts' => $posts, 'page' => $page, 'nbPages' => $nbPages]
            )
        );
    }

    /**
     * @Route("/posts/tag/{title}/{page}", name="app_blog_tag", methods={"GET"}, defaults={"page": 1}, requirements={"page": "^\d+$"})
     * @ParamConverter("post", options={"exclude": {"page"}})
     */
    public function postsByTagAction(Tag $tag, int $page): Response
    {
        /** @var PostRepository $repository */
        $repository = $this->manager->getRepository(Post::class);

        $nbPages = (int) ceil($repository->countVisiblePost($tag) / Post::NB_PER_PAGE);
        if ($page > $nbPages) {
            throw new NotFoundHttpException();
        }

        $posts = $repository->findVisiblePost(Post::NB_PER_PAGE * ($page - 1), Post::NB_PER_PAGE, $tag);

        return new Response(
            $this->twig->render(
                'blog/by_tag.html.twig',
                ['tag' => $tag, 'posts' => $posts, 'page' => $page, 'nbPages' => $nbPages]
            )
        );
    }

    /**
     * @Route("/posts/{slug}", name="app_blog_post", methods={"GET"})
     */
    public function showPostAction(Post $post): Response
    {
        return new Response(
            $this->twig->render('blog/post.html.twig', ['post' => $post])
        );
    }
}
