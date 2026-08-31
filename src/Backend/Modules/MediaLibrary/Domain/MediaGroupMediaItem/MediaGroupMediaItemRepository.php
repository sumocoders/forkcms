<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaGroupMediaItem;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MediaGroupMediaItem>
 */
final class MediaGroupMediaItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaGroupMediaItem::class);
    }

    public function getAll(
        array $mediaGroupIds,
        bool $onlyGetFirstMediaItem
    ): array {
        $queryBuilder = $this->createQueryBuilder('i')
            ->select('i, mi, g')
            ->join('i.item', 'mi')
            ->join('i.group', 'g')
            ->where('i.group = g
                AND g.id IN (:groupIds)
                AND i.item = mi
            ')
            ->setParameter('groupIds', $mediaGroupIds)
        ;

        if ($onlyGetFirstMediaItem) {
            $queryBuilder->andWhere('i.sequence = :sequence');
            $queryBuilder->setParameter('sequence', 1);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
