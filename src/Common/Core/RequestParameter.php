<?php

namespace Common\Core;

use Symfony\Component\HttpFoundation\InputBag;

/**
 * Symfony 6 made InputBag::getInt() and InputBag::getBoolean() throw a BadRequestException when a
 * parameter is present but malformed, where previously the default was returned. Fork's actions are
 * written for that older contract: they check the result against 0 or feed it to an exists() lookup
 * that renders a friendly error, so read numeric request parameters through here instead.
 *
 * Arrays keep throwing on purpose. InputBag::filter() rejects them before the flags are considered,
 * and a 400 for something like ?id[]=1 is the correct answer.
 */
final class RequestParameter
{
    public static function getInt(InputBag $bag, string $key, int $default = 0): int
    {
        return $bag->filter($key, $default, FILTER_VALIDATE_INT, ['flags' => FILTER_NULL_ON_FAILURE])
            ?? $default;
    }

    public static function getBoolean(InputBag $bag, string $key, bool $default = false): bool
    {
        return $bag->filter($key, $default, FILTER_VALIDATE_BOOL, ['flags' => FILTER_NULL_ON_FAILURE])
            ?? $default;
    }
}
