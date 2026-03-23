<?php

namespace Backend\Modules\MediaLibrary\Manager;

use Backend\Modules\MediaLibrary\Domain\MediaItem\Command\DeleteMediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class MediaItemManager
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function delete(MediaItem $mediaItem): DeleteMediaItem
    {
        $deleteMediaItem = new DeleteMediaItem($mediaItem);

        // Handle the MediaItem delete
        $this->messageBus->dispatch($deleteMediaItem);

        return $deleteMediaItem;
    }
}
