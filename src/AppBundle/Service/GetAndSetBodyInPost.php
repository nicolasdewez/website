<?php

namespace AppBundle\Service;

/**
 * Class GetAndSetBodyInPost.
 */
class GetAndSetBodyInPost
{
    /** @var string */
    private $postPath;

    /**
     * @param string $postPath
     */
    public function __construct(string $postPath)
    {
        $this->postPath = $postPath;
    }

    /**
     * @param int $idPost
     *
     * @return string
     */
    public function get(int $idPost): string
    {
        $postPath = sprintf('%s/%s.md', $this->postPath, $idPost);
//        var_dump($postPath);
//        die();
        if (!file_exists($postPath)) {
            return '';
        }

        return file_get_contents($postPath);
    }

    /**
     * @param int    $idPost
     * @param string $body
     */
    public function set(int $idPost, string $body)
    {
        $postPath = sprintf('%s/%s.md', $this->postPath, $idPost);
        file_put_contents($postPath, $body);
    }
}
