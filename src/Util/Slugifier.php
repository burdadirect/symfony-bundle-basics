<?php

namespace HBM\BasicsBundle\Util;

class Slugifier
{
    public static function slugify(?string $string, string $char = '-'): string
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
            '`' => '',
            '´' => '',
            "'" => '',
            ' ' => $char,
            '_' => $char,
            '/' => $char,
        ];

        // Replace german umlaute
        $slug = str_replace(array_keys($replacements), array_values($replacements), $string);

        // Convert remaining letters to closest ascii match (transliteration).
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);

        // Remove all chars that are not alphanumeric or a dash (or the defined replacement char).
        // See: https://docs.aws.amazon.com/ses/latest/APIReference/API_MessageTag.html
        $slug = preg_replace('/[^a-zA-Z0-9-' . $char . ']/', '', $slug);

        // Trim starting and trailing dashes.
        $slug = trim($slug, $char);

        // Remove multiple adjacent replacement characters.
        return preg_replace('/' . $char . '{2,}/', $char, $slug);
    }
}
