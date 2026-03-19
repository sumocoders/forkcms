<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock\Command;

use Backend\Core\Engine\Model;
use Backend\Core\Language\Locale;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlock;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlockRepository;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\Status;
use Common\ModuleExtraType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CopyContentBlocksToOtherLocaleHandler
{
    public function __construct(
        private ContentBlockRepository $contentBlockRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CopyContentBlocksToOtherLocale $copyContentBlocksToOtherLocale): void
    {
        $contentBlocksToCopy = $this->getContentBlocksToCopy($copyContentBlocksToOtherLocale->fromLocale);
        $id = $this->contentBlockRepository->getNextIdForLanguage($copyContentBlocksToOtherLocale->toLocale);

        array_map(
            function (ContentBlock $contentBlock) use ($copyContentBlocksToOtherLocale, &$id): void {
                $copyContentBlocksToOtherLocale->extraIdMap[$contentBlock->getExtraId()] = $this->getNewExtraId();
                $dataTransferObject = $contentBlock->getDataTransferObject();

                // Overwrite some variables
                $dataTransferObject->forOtherLocale(
                    $id++,
                    $copyContentBlocksToOtherLocale->extraIdMap[$contentBlock->getExtraId()],
                    $copyContentBlocksToOtherLocale->toLocale
                );

                $this->contentBlockRepository->add(ContentBlock::fromDataTransferObject($dataTransferObject));
            },
            $contentBlocksToCopy
        );

        $this->entityManager->flush();
    }

    private function getContentBlocksToCopy(Locale $locale): array
    {
        return (array) $this->contentBlockRepository->findBy(
            [
                'locale' => $locale,
                'status' => Status::active()
            ]
        );
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
