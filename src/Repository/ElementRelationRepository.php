<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Repository;

use BinSoul\Symfony\Bundle\Content\Entity\ElementEntity;
use BinSoul\Symfony\Bundle\Content\Entity\ElementRelationEntity;
use BinSoul\Symfony\Bundle\Doctrine\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<ElementRelationEntity>
 */
class ElementRelationRepository extends AbstractRepository
{
    /**
     * Constructs an instance of this class.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(ElementRelationEntity::class, $registry);
    }

    /**
     * @return ElementRelationEntity[]
     */
    public function loadAll(): array
    {
        /** @var ElementRelationEntity[] $result */
        $result = $this->getRepository()->findBy([]);

        return $result;
    }

    public function load(int $id): ?ElementRelationEntity
    {
        /** @var ElementRelationEntity|null $result */
        $result = $this->getRepository()->find($id);

        return $result;
    }

    /**
     * @return ElementRelationEntity[]
     */
    public function findAllByParentElement(ElementEntity $element): array
    {
        /** @var ElementRelationEntity[] $result */
        $result = $this->getRepository()->findBy(['parent' => $element]);

        return $result;
    }

    /**
     * @param ElementEntity[] $elements
     *
     * @return ElementRelationEntity[]
     */
    public function findAllByParentElements(array $elements): array
    {
        /** @var ElementRelationEntity[] $result */
        $result = $this->getRepository()->findBy(['parent' => $elements]);

        return $result;
    }

    /**
     * @return ElementRelationEntity[]
     */
    public function findAllByChildElement(ElementEntity $element): array
    {
        /** @var ElementRelationEntity[] $result */
        $result = $this->getRepository()->findBy(['child' => $element]);

        return $result;
    }

    /**
     * @param ElementEntity[] $elements
     *
     * @return ElementRelationEntity[]
     */
    public function findAllByChildElements(array $elements): array
    {
        /** @var ElementRelationEntity[] $result */
        $result = $this->getRepository()->findBy(['child' => $elements]);

        return $result;
    }

    public function save(ElementRelationEntity $entity, bool $flush = true): void
    {
        $manager = $this->getManager();
        $manager->persist($entity);

        if ($flush) {
            $manager->flush($entity);
        }
    }
}
