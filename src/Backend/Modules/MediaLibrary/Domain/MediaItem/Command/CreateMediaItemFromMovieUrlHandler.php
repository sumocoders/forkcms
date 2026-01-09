<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem\Command;

use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItemRepository;

final class CreateMediaItemFromMovieUrlHandler
{
    public function __construct(protected MediaItemRepository $mediaItemRepository)
    {
    }

    public function handle(CreateMediaItemFromMovieUrl $createMediaItemFromMovieUrl): void
    {
        /** @var MediaItem $mediaItem */
        $mediaItem = MediaItem::createFromMovieUrl(
            $createMediaItemFromMovieUrl->source,
            $createMediaItemFromMovieUrl->movieId,
            $createMediaItemFromMovieUrl->movieTitle,
            $createMediaItemFromMovieUrl->mediaFolder,
            $createMediaItemFromMovieUrl->userId
        );

        $createMediaItemFromMovieUrl->setMediaItem($mediaItem);

        $this->mediaItemRepository->add($mediaItem);
    }
}
