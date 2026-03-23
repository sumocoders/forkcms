<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaGroup\Command;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibrary\Domain\MediaGroupMediaItem\MediaGroupMediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\Exception\MediaItemNotFound;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItemRepository;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SaveMediaGroupHandler
{
    public function __construct(
        private MediaItemRepository $mediaItemRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(SaveMediaGroup $saveMediaGroup): void
    {
        /** @var MediaGroup $mediaGroup */
        $mediaGroup = MediaGroup::fromDataTransferObject($saveMediaGroup);
        $this->updateConnectedItems($mediaGroup, $saveMediaGroup->mediaItemIdsToConnect);

        // We redefine the MediaGroup, so we can use it in an action
        $saveMediaGroup->setMediaGroup($mediaGroup);
    }

    private function updateConnectedItems(MediaGroup $mediaGroup, array $mediaItemIdsToConnect): void
    {
        /**
         * @var int $sequence
         * @var string $mediaItemId
         */
        foreach ($mediaItemIdsToConnect as $sequence => $mediaItemId) {
            try {
                $mediaGroup->addConnectedItem(MediaGroupMediaItem::create(
                    $mediaGroup,
                    $this->mediaItemRepository->findOneById(Uuid::fromString($mediaItemId)),
                    $sequence
                ));
            } catch (MediaItemNotFound) {
                // Do nothing
            }
        }

        $this->entityManager->flush();
    }
}
