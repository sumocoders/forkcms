<?php

namespace Common\Core\Header;

use InvalidArgumentException;

final class JsData implements \Stringable
{
    public function __construct(private array $jsData = [])
    {
    }

    public function add(string $module, string $key, $value): void
    {
        if ($module === 'language') {
            throw new InvalidArgumentException('You are not allowed to overwrite the language');
        }

        $this->jsData[$module][$key] = $value;
    }

    public function __toString(): string
    {
        return '<script>var jsData = ' . json_encode($this->jsData) . '</script>';
    }
}
