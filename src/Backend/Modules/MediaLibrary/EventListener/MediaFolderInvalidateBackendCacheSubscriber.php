<?php

namespace Backend\Modules\MediaLibrary\EventListener;

use Backend\Modules\MediaLibrary\Builder\MediaFolder\MediaFolderCache;
use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolder;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;

/**
 * Invalidate MediaFolder Backend Cache Subscriber
 */
final class MediaFolderInvalidateBackendCacheSubscriber
{
    public function __construct(protected MediaFolderCache $mediaFolderCache)
    {
    }

    private function invalidateBackendCacheForMediaFolders(LifecycleEventArgs $eventArgs): void
    {
        if ($eventArgs->getObject() instanceof MediaFolder || $eventArgs->getObject() instanceof MediaItem) {
            $this->mediaFolderCache->delete();
        }
    }

    public function postPersist(PostPersistEventArgs $eventArgs): void
    {
        $this->invalidateBackendCacheForMediaFolders($eventArgs);
    }

    public function postUpdate(PostUpdateEventArgs $eventArgs): void
    {
        $this->invalidateBackendCacheForMediaFolders($eventArgs);
    }

    public function postRemove(PostRemoveEventArgs $eventArgs): void
    {
        $this->invalidateBackendCacheForMediaFolders($eventArgs);
    }
}
