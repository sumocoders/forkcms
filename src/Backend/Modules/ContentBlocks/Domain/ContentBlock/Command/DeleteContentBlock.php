<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock\Command;

use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlock;

final class DeleteContentBlock
{
    public function __construct(public ContentBlock $contentBlock)
    {
    }
}
