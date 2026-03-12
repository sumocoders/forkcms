<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaFolder\Command;

use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolder;
use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateMediaFolderHandler
{
    public function __construct(
        private MediaFolderRepository $mediaFolderRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CreateMediaFolder $createMediaFolder): void
    {
        /** @var MediaFolder $mediaFolder */
        $mediaFolder = MediaFolder::fromDataTransferObject($createMediaFolder);
        $this->mediaFolderRepository->add($mediaFolder);

        // We redefine the MediaFolder, so we can use it in an action
        $createMediaFolder->setMediaFolderEntity($mediaFolder);

        $this->entityManager->flush();
    }
}
