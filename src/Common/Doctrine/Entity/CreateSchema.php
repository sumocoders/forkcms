<?php

namespace Common\Doctrine\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;

class CreateSchema
{
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    /**
     * Adds a new doctrine entity in the database
     *
     * @param string $entityClass
     */
    public function forEntityClass(string $entityClass): void
    {
        $this->forEntityClasses([$entityClass]);
    }

    /**
     * Adds new doctrine entities in the database
     *
     * @param string[] $entityClasses
     *
     * @throws ToolsException
     */
    public function forEntityClasses(array $entityClasses): void
    {
        $classMetadata = array_map($this->entityManager->getClassMetadata(...), $entityClasses);

        // Doctrine ORM 3 dropped updateSchema()'s save mode, so the schema diff would drop every table
        // that isn't backed by one of the given entities - which is most of Fork's (non entity) tables.
        // Restricting the assets filter keeps the introspection limited to the tables we are creating.
        $configuration = $this->entityManager->getConnection()->getConfiguration();
        $previousFilter = $configuration->getSchemaAssetsFilter();
        $configuration->setSchemaAssetsFilter(static fn (): bool => false);

        try {
            (new SchemaTool($this->entityManager))->updateSchema($classMetadata);
        } finally {
            $configuration->setSchemaAssetsFilter($previousFilter);
        }
    }
}
