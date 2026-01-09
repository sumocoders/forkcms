<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem\Command;

use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolder;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\StorageType;

final class CreateMediaItemFromMovieUrl
{
    private ?MediaItem $mediaItem = null;

    public function __construct(
        public StorageType $source,
        public string $movieId,
        public string $movieTitle,
        public MediaFolder $mediaFolder,
        public int $userId,
    ) {
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
