<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock\Command;

use Backend\Core\Engine\Model;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlock;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlockRepository;

final class DeleteContentBlockHandler
{
    public function __construct(private readonly ContentBlockRepository $contentBlockRepository)
    {
    }

    public function handle(DeleteContentBlock $deleteContentBlock): void
    {
        $this->contentBlockRepository->removeByIdAndLocale(
            $deleteContentBlock->contentBlock->getId(),
            $deleteContentBlock->contentBlock->getLocale()
        );

        Model::deleteExtraById($deleteContentBlock->contentBlock->getExtraId());
    }
}
