<?php

namespace Common;

use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * This is our Uri generating class
 */
class Uri
{
    /**
     * Prepares a string so that it can be used in urls.
     *
     * @param string $value The value that should be urlized.
     *
     * @return string  The urlized string.
     */
    public static function getUrl(string $value): string
    {
        $slugger = new AsciiSlugger();

        return $slugger->slug($value)->lower();
    }
}
