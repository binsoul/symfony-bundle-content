<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Repository;

use BinSoul\Symfony\Bundle\Content\Entity\ElementEntity;
use BinSoul\Symfony\Bundle\Content\Entity\PageElementEntity;
use BinSoul\Symfony\Bundle\Content\Entity\PageEntity;
use BinSoul\Symfony\Bundle\Doctrine\Repository\AbstractRepository;
use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<PageElementEntity>
 */
class PageElementRepository extends AbstractRepository
{
    /**
     * Constructs an instance of this class.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(PageElementEntity::class, $registry);
    }

    /**
     * @return PageElementEntity[]
     */
    public function loadAll(): array
    {
        /** @var PageElementEntity[] $result */
        $result = $this->getRepository()->findBy([]);

        return $result;
    }

    public function load(int $id): ?PageElementEntity
    {
        /** @var PageElementEntity|null $result */
        $result = $this->getRepository()->find($id);

        return $result;
    }

    /**
     * @return PageElementEntity[]
     */
    public function findAllByPage(PageEntity $page): array
    {
        /** @var PageElementEntity[] $result */
        $result = $this->getRepository()->findBy(['page' => $page]);

        return $result;
    }

    /**
     * @return PageElementEntity[]
     */
    public function findAllByPageAndLocale(PageEntity $page, LocaleEntity $locale): array
    {
        /** @var PageElementEntity[] $result */
        $result = $this->getRepository()->findBy(['page' => $page, 'locale' => $locale]);

        return $result;
    }

    /**
     * @return PageElementEntity[]
     */
    public function findAllByElement(ElementEntity $element): array
    {
        /** @var PageElementEntity[] $result */
        $result = $this->getRepository()->findBy(['element' => $element]);

        return $result;
    }

    public function save(PageElementEntity $entity, bool $flush = true): void
    {
        $manager = $this->getManager();
        $manager->persist($entity);

        if ($flush) {
            $manager->flush($entity);
        }
    }
}
