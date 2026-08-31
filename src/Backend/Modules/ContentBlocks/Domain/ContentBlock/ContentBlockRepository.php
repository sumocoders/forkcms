<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock;

use Backend\Modules\ContentBlocks\Domain\ContentBlock\Exception\ContentBlockNotFound;
use Common\Locale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContentBlock>
 */
class ContentBlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentBlock::class);
    }

    public function add(ContentBlock $contentBlock): void
    {
        // make sure the other revisions are archived
        if ($contentBlock->getStatus()->isActive() && $contentBlock->getId() !== null) {
            array_map(
                function (ContentBlock $contentBlock): void {
                    $contentBlock->archive();
                },
                (array) $this->findBy(['id' => $contentBlock->getId(), 'locale' => $contentBlock->getLocale()])
            );
        }

        $this->getEntityManager()->persist($contentBlock);
        $this->save();
    }

    public function getNextIdForLanguage(Locale $locale): int
    {
        return (int) $this->getEntityManager()
            ->createQueryBuilder()
            ->select('MAX(cb.id) as id')
            ->from(ContentBlock::class, 'cb')
            ->where('cb.locale = :locale')
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getSingleScalarResult() + 1;
    }

    public function findOneByIdAndLocale(?int $id, Locale $locale): ?ContentBlock
    {
        if ($id === null) {
            throw ContentBlockNotFound::forEmptyId();
        }

        /** @var ContentBlock $contentBlock */
        $contentBlock = $this->findOneBy(['id' => $id, 'status' => Status::active(), 'locale' => $locale]);

        if ($contentBlock === null) {
            throw ContentBlockNotFound::forId($id);
        }

        return $contentBlock;
    }

    public function findOneByRevisionIdAndLocale(?int $revisionId, Locale $locale): ContentBlock
    {
        if ($revisionId === null) {
            throw ContentBlockNotFound::forEmptyRevisionId();
        }

        /** @var ContentBlock|null ContentBlock */
        $contentBlock = $this->findOneBy(
            ['revisionId' => $revisionId, 'locale' => $locale]
        );

        if ($contentBlock === null) {
            throw ContentBlockNotFound::forRevisionId($revisionId);
        }

        return $contentBlock;
    }

    public function removeByIdAndLocale($id, Locale $locale): void
    {
        array_map(
            function (ContentBlock $contentBlock): void {
                $this->getEntityManager()->remove($contentBlock);
            },
            (array) $this->findBy(['id' => $id, 'locale' => $locale])
        );

        $this->save();
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}
