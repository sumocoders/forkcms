<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem\Command;

use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;

final class DeleteMediaItem
{
    public function __construct(public MediaItem $mediaItem)
    {
    }
}
