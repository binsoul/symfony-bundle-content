<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Repository;

use BinSoul\Symfony\Bundle\Content\Entity\ElementEntity;
use BinSoul\Symfony\Bundle\Doctrine\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<ElementEntity>
 */
class ElementRepository extends AbstractRepository
{
    /**
     * Constructs an instance of this class.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(ElementEntity::class, $registry);
    }

    /**
     * @return ElementEntity[]
     */
    public function loadAll(): array
    {
        /** @var ElementEntity[] $result */
        $result = $this->getRepository()->findBy([]);

        return $result;
    }

    public function load(int $id): ?ElementEntity
    {
        /** @var ElementEntity|null $result */
        $result = $this->getRepository()->find($id);

        return $result;
    }

    public function save(ElementEntity $entity, bool $flush = true): void
    {
        $manager = $this->getManager();
        $manager->persist($entity);

        if ($flush) {
            $manager->flush($entity);
        }
    }
}
