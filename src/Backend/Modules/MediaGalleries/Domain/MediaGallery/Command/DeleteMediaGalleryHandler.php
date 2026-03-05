<?php

namespace Backend\Modules\MediaGalleries\Domain\MediaGallery\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGalleryRepository;
use Backend\Modules\MediaLibrary\Domain\MediaGroupMediaItem\MediaGroupMediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteMediaGalleryHandler
{
    public function __construct(
        private MediaGalleryRepository $mediaGalleryRepository,
        private MediaItemRepository $mediaItemRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(DeleteMediaGallery $deleteMediaGallery): void
    {
        // We should delete all MediaItem entities which were connected to this MediaGallery
        if ($deleteMediaGallery->deleteAllMediaItems) {
            /** @var ArrayCollection|MediaGroupMediaItem $mediaGroupMediaItems */
            $mediaGroupMediaItems = $deleteMediaGallery->mediaGallery->getMediaGroup()->getConnectedItems();

            /** @var MediaGroupMediaItem $mediaGroupMediaItem */
            foreach ($mediaGroupMediaItems->getValues() as $mediaGroupMediaItem) {
                // Delete MediaItem
                $this->mediaItemRepository->remove($mediaGroupMediaItem->getItem());
            }
        }

        $this->mediaGalleryRepository->remove($deleteMediaGallery->mediaGallery);

        $this->entityManager->flush();
    }
}
