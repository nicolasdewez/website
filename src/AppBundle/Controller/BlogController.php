<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Energy;
use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use AppBundle\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * @param int $page
     *
     * @return Response
     *
     * @Route("/{page}", name="app_blog", methods={"GET"}, defaults={"page": 1}, requirements={"page": "^\d+$"})
     */
    public function homeAction(int $page): Response
    {
        /** @var PostRepository $repository */
        $repository = $this->get('doctrine.orm.default_entity_manager')->getRepository(Post::class);

        $nbPages = (int) ceil($repository->countVisiblePost()  / Post::NB_PER_PAGE);
        if ($page > $nbPages) {
            throw new NotFoundHttpException();
        }

        $posts = $repository->findVisiblePost(Post::NB_PER_PAGE * ($page - 1), Post::NB_PER_PAGE);

        return $this->render('blog/home.html.twig', ['posts' => $posts, 'page' => $page, 'nbPages' => $nbPages]);
    }

    /**
     * @param Tag $tag
     * @param int $page
     *
     * @return Response
     *
     * @Route("/posts/tag/{title}/{page}", name="app_blog_tag", methods={"GET"}, defaults={"page": 1}, requirements={"page": "^\d+$"})
     * @ParamConverter("post", options={"exclude": {"page"}})
     */
    public function postsByTagAction(Tag $tag, int $page): Response
    {
        /** @var PostRepository $repository */
        $repository = $this->get('doctrine.orm.default_entity_manager')->getRepository(Post::class);

        $nbPages = (int) ceil($repository->countVisiblePost($tag)  / Post::NB_PER_PAGE);
        if ($page > $nbPages) {
            throw new NotFoundHttpException();
        }

        $posts = $repository->findVisiblePost(Post::NB_PER_PAGE * ($page - 1), Post::NB_PER_PAGE, $tag);

        return $this->render('blog/by_tag.html.twig', ['tag' => $tag, 'posts' => $posts, 'page' => $page, 'nbPages' => $nbPages]);
    }

    /**
     * @param Post $post
     *
     * @return Response
     *
     * @Route("/posts/{slug}", name="app_blog_post", methods={"GET"})
     */
    public function showPostAction(Post $post): Response
    {
        return $this->render('blog/post.html.twig', ['post' => $post]);
    }
}
