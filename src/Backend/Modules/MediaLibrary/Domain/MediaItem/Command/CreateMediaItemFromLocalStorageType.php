<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem\Command;

use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolder;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;

final class CreateMediaItemFromLocalStorageType
{
    /** @var MediaItem */
    private $mediaItem;

    public function __construct(public string $path, public MediaFolder $mediaFolder, public int $userId = 0)
    {
    }

    public function getMediaItem(): MediaItem
    {
        return $this->mediaItem;
    }

    public function setMediaItem(MediaItem $mediaItem): void
    {
        $this->mediaItem = $mediaItem;
    }
}
