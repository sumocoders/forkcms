<?php

namespace Backend\Modules\MediaLibrary\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model;
use Backend\Modules\MediaLibrary\Domain\MediaFolder\Command\DeleteMediaFolder;
use Backend\Modules\MediaLibrary\Domain\MediaFolder\Exception\MediaFolderNotFound;
use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolder;
use Symfony\Component\Messenger\MessageBusInterface;

class MediaFolderDelete extends BackendBaseActionDelete
{
    public function execute(): void
    {
        parent::execute();

        $this->abortWhenLastFolder();

        /** @var DeleteMediaFolder $deleteMediaFolder */
        $deleteMediaFolder = $this->deleteMediaFolder();

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'media-folder-deleted',
                    'var' => urlencode($deleteMediaFolder->mediaFolder->getName()),
                ]
            )
        );
    }

    private function abortWhenLastFolder(): void
    {
        // If this is the last folder, delete is not possible
        if (count($this->get('media_library.repository.folder')->findAll()) === 1) {
            $this->redirect(
                $this->getBackLink(
                    [
                        'error' => 'media-folder-delete-not-possible',
                    ]
                )
            );
        }
    }

    private function checkIfDeleteIsAllowed(MediaFolder $mediaFolder): void
    {
        // If folder has children/items, delete is not possible
        if ($mediaFolder->hasConnectedItems() && $mediaFolder->hasChildrenWithConnectedItems()) {
            $this->redirect(
                $this->getBackLink(
                    [
                        'error' => 'media-folder-delete-not-possible-because-of-connected-media-items',
                    ]
                )
            );
        }

        if ($mediaFolder->hasChildren()) {
            $this->redirect(
                $this->getBackLink(
                    [
                        'error' => 'media-folder-delete-not-possible',
                    ]
                )
            );
        }
    }

    private function deleteMediaFolder(): DeleteMediaFolder
    {
        $mediaFolder = $this->getMediaFolder();

        $this->checkIfDeleteIsAllowed($mediaFolder);

        $deleteMediaFolder = new DeleteMediaFolder($mediaFolder);

        // Handle the MediaFolder delete
        /** @var MessageBusInterface $messageBus */
        $messageBus = $this->get('messenger.default_bus');
        $messageBus->dispatch($deleteMediaFolder);

        return $deleteMediaFolder;
    }

    private function getMediaFolder(): MediaFolder
    {
        try {
            /** @var MediaFolder */
            return $this->get('media_library.repository.folder')->findOneById(
                $this->getRequest()->query->getInt('id')
            );
        } catch (MediaFolderNotFound) {
            $this->redirect(
                $this->getBackLink(
                    [
                        'error' => 'non-existing',
                    ]
                )
            );
        }
    }

    private function getBackLink(array $parameters = []): string
    {
        return Model::createUrlForAction(
            'MediaItemIndex',
            null,
            null,
            $parameters
        );
    }
}
