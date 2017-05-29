<?php

namespace AppBundle\Service;

/**
 * Class DefineSlug.
 */
class DefineSlug
{
    /**
     * @param string $title
     *
     * @return string
     */
    public function build(string $title): string
    {
        $slug = strtolower($title);
        $slug = trim(str_replace('/', '', $slug));
        $slug = str_replace([' ', '-', '\'', '"'], '_', $slug);
        $slug = str_replace(['à', 'â', 'ä'], 'a', $slug);
        $slug = str_replace(['é', 'è', 'ê', 'ë'], 'e', $slug);
        $slug = str_replace(['ì', 'î', 'ï'], 'i', $slug);
        $slug = str_replace(['ò', 'ô', 'ö'], 'o', $slug);
        $slug = str_replace(['ù', 'û', 'ü'], 'u', $slug);

        return $slug;
    }
}
