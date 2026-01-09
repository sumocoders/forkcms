<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaFolder\Command;

use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolder;

final class DeleteMediaFolder
{
    public function __construct(public MediaFolder $mediaFolder)
    {
    }
}
