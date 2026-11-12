<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class StatusDBALType extends StringType
{
    const CONTENT_BLOCKS_STATUS = 'content_blocks_status';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] ??= 255;

        return parent::getSQLDeclaration($column, $platform);
    }

    public function convertToPHPValue($status, AbstractPlatform $platform): Status
    {
        return Status::fromString($status);
    }

    public function convertToDatabaseValue($status, AbstractPlatform $platform): string
    {
        return (string) $status;
    }

    public function getName(): string
    {
        return self::CONTENT_BLOCKS_STATUS;
    }
}
