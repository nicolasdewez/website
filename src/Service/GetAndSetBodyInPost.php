<?php

namespace App\Service;

class GetAndSetBodyInPost
{
    /** @var string */
    private $postPath;

    public function __construct(string $postPath)
    {
        $this->postPath = $postPath;
    }

    public function get(int $idPost): string
    {
        $postPath = sprintf('%s/%s.md', $this->postPath, $idPost);
        if (!file_exists($postPath)) {
            return '';
        }

        return file_get_contents($postPath);
    }

    public function set(int $idPost, string $body): void
    {
        $postPath = sprintf('%s/%s.md', $this->postPath, $idPost);
        file_put_contents($postPath, $body);
    }
}
