<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem\Command;

use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItemRepository;

final class DeleteMediaItemHandler
{
    public function __construct(private readonly MediaItemRepository $mediaItemRepository)
    {
    }

    public function handle(DeleteMediaItem $deleteMediaItem): void
    {
        $this->mediaItemRepository->remove($deleteMediaItem->mediaItem);
    }
}
