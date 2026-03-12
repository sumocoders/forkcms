<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock\Command;

use Backend\Core\Engine\Model;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlock;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlockRepository;
use Common\ModuleExtraType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateContentBlockHandler
{
    public function __construct(
        private ContentBlockRepository $contentBlockRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CreateContentBlock $createContentBlock): void
    {
        $createContentBlock->extraId = $this->getNewExtraId();
        $createContentBlock->id = $this->contentBlockRepository->getNextIdForLanguage($createContentBlock->locale);

        $contentBlock = ContentBlock::fromDataTransferObject($createContentBlock);
        $this->contentBlockRepository->add($contentBlock);

        $createContentBlock->setContentBlockEntity($contentBlock);

        $this->entityManager->flush();
    }

    private function getNewExtraId(): int
    {
        return Model::insertExtra(
            ModuleExtraType::widget(),
            'ContentBlocks',
            'Detail'
        );
    }
}
