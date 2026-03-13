<?php

namespace Backend\Modules\MediaGalleries\Domain\MediaGallery\Command;

use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGallery;
use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateMediaGalleryHandler
{
    public function __construct(
        private MediaGalleryRepository $mediaGalleryRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CreateMediaGallery $createMediaGallery): void
    {
        $mediaGallery = MediaGallery::fromDataTransferObject($createMediaGallery);
        $this->mediaGalleryRepository->add($mediaGallery);

        // We redefine the mediaGallery, so we can use it in an action
        $createMediaGallery->setMediaGalleryEntity($mediaGallery);

        $this->entityManager->flush();
    }
}
