<?php

namespace Backend\Modules\MediaGalleries\Domain\MediaGallery\Command;

use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGallery;
use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGalleryRepository;

final class CreateMediaGalleryHandler
{
    public function __construct(private MediaGalleryRepository $mediaGalleryRepository)
    {
    }

    public function handle(CreateMediaGallery $createMediaGallery): void
    {
        /** @var MediaGallery $mediaGallery */
        $mediaGallery = MediaGallery::fromDataTransferObject($createMediaGallery);
        $this->mediaGalleryRepository->add($mediaGallery);

        // We redefine the mediaGallery, so we can use it in an action
        $createMediaGallery->setMediaGalleryEntity($mediaGallery);
    }
}
