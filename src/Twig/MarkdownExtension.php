<?php

namespace App\Twig;

class MarkdownExtension extends \Twig_Extension
{
    /** @var \Parsedown */
    private $parsedown;

    public function __construct(\Parsedown $parsedown)
    {
        $this->parsedown = $parsedown;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('markdown_parse', [$this, 'parse']),
        ];
    }

    public function parse(string $content): string
    {
        return $this->parsedown->text($content);
    }
}
