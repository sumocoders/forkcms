<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock\Event;

use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlock;
use Symfony\Contracts\EventDispatcher\Event;

abstract class ContentBlockEvent extends Event
{
    public function __construct(private ContentBlock $contentBlock)
    {
    }

    public function getContentBlock(): ContentBlock
    {
        return $this->contentBlock;
    }
}
