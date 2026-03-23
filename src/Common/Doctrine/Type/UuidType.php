<?php

namespace Common\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\ConversionException;
use Throwable;
use Symfony\Component\Uid\Uuid;

final class UuidType extends GuidType
{
    public const NAME = 'uuid';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
    {
        if ($value instanceof Uuid) {
            return $value;
        }

        if (!is_string($value) || $value === '') {
            return null;
        }

        try {
            return Uuid::fromString($value);
        } catch (Throwable) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof Uuid) {
            return $value->toRfc4122();
        }

        if (is_string($value) || (is_object($value) && method_exists($value, '__toString'))) {
            $string = (string) $value;

            if ($string !== '' && Uuid::isValid($string)) {
                return $string;
            }
        }

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /** @return string[] */
    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [self::NAME];
    }
}
