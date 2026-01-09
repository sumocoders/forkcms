<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock\Command;

use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlock;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlockRepository;

final class UpdateContentBlockHandler
{
    public function __construct(private ContentBlockRepository $contentBlockRepository)
    {
    }

    public function handle(UpdateContentBlock $updateContentBlock): void
    {
        $contentBlock = ContentBlock::fromDataTransferObject($updateContentBlock);
        $this->contentBlockRepository->add($contentBlock);

        $updateContentBlock->setContentBlockEntity($contentBlock);
    }
}
