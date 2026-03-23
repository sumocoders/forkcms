<?php

namespace Backend\Modules\MediaGalleries\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model;
use Backend\Form\Type\DeleteType;
use Backend\Modules\MediaGalleries\Domain\MediaGallery\Command\DeleteMediaGallery;
use Backend\Modules\MediaGalleries\Domain\MediaGallery\Exception\MediaGalleryNotFound;
use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGallery;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * This is the class to Delete a MediaGallery
 */
class MediaGalleryDelete extends BackendBaseActionDelete
{
    public function execute(): void
    {
        parent::execute();

        $deleteForm = $this->createForm(
            DeleteType::class,
            null,
            ['module' => $this->getModule(), 'action' => 'MediaGalleryDelete']
        );
        $deleteForm->handleRequest($this->getRequest());
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->redirect(Model::createUrlForAction('MediaGalleryIndex') . '&error=something-went-wrong');
        }
        $deleteFormData = $deleteForm->getData();

        /** @var MediaGallery $mediaGallery */
        $mediaGallery = $this->getMediaGallery($deleteFormData['id']);

        $deleteMediaGallery = new DeleteMediaGallery($mediaGallery);

        // Handle the MediaGallery delete
        /** @var MessageBusInterface $messageBus */
        $messageBus = $this->get('messenger.default_bus');
        $messageBus->dispatch($deleteMediaGallery);

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'media-gallery-deleted',
                    'var' => $deleteMediaGallery->mediaGallery->getTitle(),
                ]
            )
        );
    }

    private function getMediaGallery(string $id): MediaGallery
    {
        try {
            /** @var MediaGallery|null $mediaGallery */
            return $this->get('media_galleries.repository.gallery')->findOneById($id);
        } catch (MediaGalleryNotFound) {
            $this->redirect(
                $this->getBackLink(
                    [
                        'error' => 'non-existing-media-gallery',
                    ]
                )
            );
        }
    }

    private function getBackLink(array $parameters = []): string
    {
        return Model::createUrlForAction(
            'MediaGalleryIndex',
            null,
            null,
            $parameters
        );
    }
}
