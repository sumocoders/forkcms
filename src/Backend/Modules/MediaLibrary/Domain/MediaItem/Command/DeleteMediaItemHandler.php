<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem\Command;

use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteMediaItemHandler
{
    public function __construct(
        private MediaItemRepository $mediaItemRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(DeleteMediaItem $deleteMediaItem): void
    {
        $this->mediaItemRepository->remove($deleteMediaItem->mediaItem);
        $this->entityManager->flush();
    }
}
