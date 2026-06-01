<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaFolder\Command;

use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolder;
use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateMediaFolderHandler
{
    public function __construct(
        private MediaFolderRepository $mediaFolderRepository,
    ) {
    }

    public function __invoke(UpdateMediaFolder $updateMediaFolder): void
    {
        // We redefine the MediaFolder, so we can use it in an action
        $updateMediaFolder->setMediaFolderEntity(MediaFolder::fromDataTransferObject($updateMediaFolder));

        $this->mediaFolderRepository->save();
    }
}
