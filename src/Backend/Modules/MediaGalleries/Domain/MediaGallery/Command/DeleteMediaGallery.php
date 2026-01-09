<?php

namespace Backend\Modules\MediaGalleries\Domain\MediaGallery\Command;

use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGallery;

final class DeleteMediaGallery
{
    public function __construct(public MediaGallery $mediaGallery, public bool $deleteAllMediaItems = false)
    {
    }
}
