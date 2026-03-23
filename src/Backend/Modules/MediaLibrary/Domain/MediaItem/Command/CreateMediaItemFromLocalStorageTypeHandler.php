<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem\Command;

use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateMediaItemFromLocalStorageTypeHandler
{
    public function __construct(
        private MediaItemRepository $mediaItemRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CreateMediaItemFromLocalStorageType $createMediaItemFromLocalStorageType): void
    {
        /** @var MediaItem $mediaItem */
        $mediaItem = MediaItem::createFromLocalStorageType(
            $createMediaItemFromLocalStorageType->path,
            $createMediaItemFromLocalStorageType->mediaFolder,
            $createMediaItemFromLocalStorageType->userId
        );

        $this->mediaItemRepository->add($mediaItem);

        $createMediaItemFromLocalStorageType->setMediaItem($mediaItem);

        dump($mediaItem);
        $this->entityManager->flush();
        dump('flushed');
    }
}
