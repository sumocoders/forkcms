<?php

namespace Backend\Modules\MediaGalleries\Domain\MediaGallery\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGalleryRepository;
use Backend\Modules\MediaLibrary\Domain\MediaGroupMediaItem\MediaGroupMediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItemRepository;

final class DeleteMediaGalleryHandler
{
    public function __construct(private MediaGalleryRepository $mediaGalleryRepository, private MediaItemRepository $mediaItemRepository)
    {
    }

    public function handle(DeleteMediaGallery $deleteMediaGallery): void
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
    }
}
