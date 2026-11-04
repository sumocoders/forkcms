<?php

namespace Backend\Modules\MediaLibrary\EventListener;

use Backend\Modules\MediaLibrary\Manager\FileManager;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * MediaItem Subscriber
 */
final class MediaItemSubscriber
{
    public function __construct(
        protected FileManager $fileManager,
        protected CacheManager $cacheManager,
    ) {
    }

    private function deleteSource(MediaItem $mediaItem): void
    {
        $this->fileManager->deleteFile($mediaItem->getAbsolutePath());
    }

    private function deleteThumbnails(MediaItem $mediaItem): void
    {
        // We have an image, so we have thumbnails to delete
        if ($mediaItem->getType()->isImage()) {
            // Delete all thumbnails
            $this->cacheManager->remove($mediaItem->getWebPath());
        }
    }

    private function generateThumbnails(MediaItem $mediaItem): void
    {
        // We have an image, so we have a backend thumbnail to generate
        if ($mediaItem->getType()->isImage()) {
            // Generate Backend thumbnail
            $this->cacheManager->getBrowserPath($mediaItem->getWebPath(), 'media_library_backend_thumbnail');
        }
    }

    public function postPersist(PostPersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if (!$entity instanceof MediaItem) {
            return;
        }

        $this->generateThumbnails($entity);
    }

    public function postRemove(PostRemoveEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if (!$entity instanceof MediaItem) {
            return;
        }

        $this->deleteSource($entity);
        $this->deleteThumbnails($entity);
    }
}
