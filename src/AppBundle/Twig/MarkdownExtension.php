<?php

namespace AppBundle\Twig;

/**
 * Class MarkdownExtension.
 */
class MarkdownExtension extends \Twig_Extension
{
    /** @var \Parsedown */
    private $parsedown;

    /**
     * @param \Parsedown $parsedown
     */
    public function __construct(\Parsedown $parsedown)
    {
        $this->parsedown = $parsedown;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('markdown_parse', array($this, 'parse')),
        );
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function parse(string $content): string
    {
        return $this->parsedown->text($content);
    }
}
