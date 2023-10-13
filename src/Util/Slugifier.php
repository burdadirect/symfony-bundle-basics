<?php

namespace HBM\BasicsBundle\Util;

class Slugifier
{
    /**
     * @param string|null $string
     *
     * @return string
     */
    public static function slugify(?string $string): string
    {
        if ($string === null) {
            return '';
        }

        $replacements = [
          'ä' => 'ae',
          'Ä' => 'Ae',
          'ö' => 'oe',
          'Ö' => 'Oe',
          'ü' => 'ue',
          'Ü' => 'ue',
          'ß' => 'ss',
        ];

        $slug = $string;
        // Replace german umlaute
        $slug = str_replace(
          array_keys($replacements),
          array_values($replacements),
          $slug
        );
        // Convert remaining letters to closest ascii match.
        $slug = iconv('utf-8', 'ascii//TRANSLIT//IGNORE', $slug);
        // Replace spaces with dashes.
        $slug = str_replace([' '], ['-'], $slug);
        // Replace everthings thats not allowed: https://docs.aws.amazon.com/ses/latest/APIReference/API_MessageTag.html
        $slug = preg_replace('/[^a-zA-Z0-9-_]/', '', $slug);
        // Remove multiple dashes.
        $slug = preg_replace('/-+/', '-', $slug);

        return $slug;
    }
}
