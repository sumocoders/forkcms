<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class AspectRatioDBALType extends Type
{
    public function getName(): string
    {
        return 'media_item_aspect_ratio';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        // DBAL 4 columns default the scale to 0 (not null), so ??= would never apply and the diff wants NUMERIC(13, 0)
        $column['precision'] = 13;
        $column['scale'] = 2;

        return $platform->getDecimalTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($aspectRatio, AbstractPlatform $platform): ?AspectRatio
    {
        if ($aspectRatio === null) {
            return null;
        }

        return new AspectRatio($aspectRatio);
    }

    public function convertToDatabaseValue($aspectRatio, AbstractPlatform $platform): ?float
    {
        if ($aspectRatio === null) {
            return null;
        }

        return $aspectRatio->asFloat();
    }
}
