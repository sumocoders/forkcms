<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem\Command;

use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateMediaItemFromMovieUrlHandler
{
    public function __construct(
        private MediaItemRepository $mediaItemRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CreateMediaItemFromMovieUrl $createMediaItemFromMovieUrl): void
    {
        $mediaItem = MediaItem::createFromMovieUrl(
            $createMediaItemFromMovieUrl->source,
            $createMediaItemFromMovieUrl->movieId,
            $createMediaItemFromMovieUrl->movieTitle,
            $createMediaItemFromMovieUrl->mediaFolder,
            $createMediaItemFromMovieUrl->userId
        );

        $createMediaItemFromMovieUrl->setMediaItem($mediaItem);

        $this->mediaItemRepository->add($mediaItem);

        $this->entityManager->flush();
    }
}
