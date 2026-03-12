<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock\Command;

use Backend\Core\Engine\Model;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteContentBlockHandler
{
    public function __construct(
        private ContentBlockRepository $contentBlockRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(DeleteContentBlock $deleteContentBlock): void
    {
        $this->contentBlockRepository->removeByIdAndLocale(
            $deleteContentBlock->contentBlock->getId(),
            $deleteContentBlock->contentBlock->getLocale()
        );

        Model::deleteExtraById($deleteContentBlock->contentBlock->getExtraId());

        $this->entityManager->flush();
    }
}
