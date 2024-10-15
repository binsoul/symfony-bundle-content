<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Repository;

use BinSoul\Symfony\Bundle\Content\Entity\PageEntity;
use BinSoul\Symfony\Bundle\Content\Entity\PageTranslationEntity;
use BinSoul\Symfony\Bundle\Doctrine\Repository\AbstractRepository;
use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<PageTranslationEntity>
 */
class PageTranslationRepository extends AbstractRepository
{
    /**
     * Constructs an instance of this class.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(PageTranslationEntity::class, $registry);
    }

    /**
     * @return PageTranslationEntity[]
     */
    public function loadAll(): array
    {
        /** @var PageTranslationEntity[] $result */
        $result = $this->getRepository()->findBy([]);

        return $result;
    }

    public function load(int $id): ?PageTranslationEntity
    {
        /** @var PageTranslationEntity|null $result */
        $result = $this->getRepository()->find($id);

        return $result;
    }

    /**
     * @return PageTranslationEntity[]
     */
    public function findAllByPage(PageEntity $page): array
    {
        return $this->getRepository()->findBy(['page' => $page]);
    }

    public function findByPageAndLocale(PageEntity $page, LocaleEntity $locale): ?PageTranslationEntity
    {
        /** @var PageTranslationEntity|null $result */
        $result = $this->getRepository()->findOneBy(['page' => $page, 'locale' => $locale]);

        return $result;
    }

    public function save(PageTranslationEntity $entity, bool $flush = true): void
    {
        $manager = $this->getManager();
        $manager->persist($entity);

        if ($flush) {
            $manager->flush($entity);
        }
    }
}
