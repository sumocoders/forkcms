<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaFolder\Command;

use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteMediaFolderHandler
{
    public function __construct(
        private MediaFolderRepository $mediaFolderRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(DeleteMediaFolder $deleteMediaFolder): void
    {
        $mediaFolder = $deleteMediaFolder->mediaFolder;

        $this->mediaFolderRepository->remove($mediaFolder);

        $this->entityManager->flush();
    }
}
