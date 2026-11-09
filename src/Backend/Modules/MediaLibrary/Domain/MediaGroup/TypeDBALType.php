<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaGroup;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class TypeDBALType extends StringType
{
    const string NAME = 'media_group_type';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] ??= 255;

        return parent::getSQLDeclaration($column, $platform);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Type
    {
        return Type::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }
}
