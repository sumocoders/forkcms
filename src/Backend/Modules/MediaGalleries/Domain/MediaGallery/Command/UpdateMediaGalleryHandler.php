<?php

namespace Backend\Modules\MediaGalleries\Domain\MediaGallery\Command;

use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGallery;
use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateMediaGalleryHandler
{
    public function __construct(
        private MediaGalleryRepository $mediaGalleryRepository,
    ) {
    }

    public function __invoke(UpdateMediaGallery $updateMediaGallery): void
    {
        // We redefine the mediaGallery, so we can use it in an action
        $updateMediaGallery->setMediaGalleryEntity(MediaGallery::fromDataTransferObject($updateMediaGallery));

        $this->mediaGalleryRepository->save();
    }
}
