<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem\Command;

use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItemRepository;

final class CreateMediaItemFromLocalStorageTypeHandler
{
    public function __construct(protected MediaItemRepository $mediaItemRepository)
    {
    }

    public function handle(CreateMediaItemFromLocalStorageType $createMediaItemFromLocalStorageType): void
    {
        /** @var MediaItem $mediaItem */
        $mediaItem = MediaItem::createFromLocalStorageType(
            $createMediaItemFromLocalStorageType->path,
            $createMediaItemFromLocalStorageType->mediaFolder,
            $createMediaItemFromLocalStorageType->userId
        );

        $this->mediaItemRepository->add($mediaItem);

        $createMediaItemFromLocalStorageType->setMediaItem($mediaItem);
    }
}
