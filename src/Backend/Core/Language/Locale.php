<?php

namespace Backend\Core\Language;

use Common\Locale as CommonLocale;

final class Locale extends CommonLocale
{
    public static function workingLocale(): self
    {
        return new self(Language::getWorkingLanguage());
    }

    protected function getPossibleLanguages(): array
    {
        return Language::getWorkingLanguages();
    }

    public function __serialize(): array
    {
        return [$this->serialize()];
    }

    public function __unserialize(array $data): void
    {
        $this->unserialize($data[0]);
    }
}
