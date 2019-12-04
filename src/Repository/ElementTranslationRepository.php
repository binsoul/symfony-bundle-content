<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Repository;

use BinSoul\Symfony\Bundle\Content\Entity\ElementEntity;
use BinSoul\Symfony\Bundle\Content\Entity\ElementTranslationEntity;
use BinSoul\Symfony\Bundle\Doctrine\Repository\AbstractRepository;
use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;
use Doctrine\Common\Persistence\ManagerRegistry;

class ElementTranslationRepository extends AbstractRepository
{
    /**
     * Constructs an instance of this class.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(ElementTranslationEntity::class, $registry);
    }

    /**
     * @return ElementTranslationEntity[]
     */
    public function loadAll(): array
    {
        /** @var ElementTranslationEntity[] $result */
        $result = $this->getRepository()->findBy([]);

        return $result;
    }

    public function load(int $id): ?ElementTranslationEntity
    {
        /** @var ElementTranslationEntity|null $result */
        $result = $this->getRepository()->find($id);

        return $result;
    }

    /**
     * @return ElementTranslationEntity[]
     */
    public function findAllByElement(ElementEntity $element): array
    {
        /** @var ElementTranslationEntity[] $result */
        $result = $this->getRepository()->findBy(['element' => $element]);

        return $result;
    }

    /**
     * @param ElementEntity[] $elements
     *
     * @return ElementTranslationEntity[]
     */
    public function findAllByElements(array $elements): array
    {
        /** @var ElementTranslationEntity[] $result */
        $result = $this->getRepository()->findBy(['element' => $elements]);

        return $result;
    }

    public function findByElementAndLocale(ElementEntity $element, LocaleEntity $locale): ?ElementTranslationEntity
    {
        /** @var ElementTranslationEntity|null $result */
        $result = $this->getRepository()->findOneBy(['element' => $element, 'locale' => $locale]);

        return $result;
    }

    /**
     * @param ElementEntity[] $elements
     *
     * @return ElementTranslationEntity[]
     */
    public function findAllByElementsAndLocale(array $elements, LocaleEntity $locale): array
    {
        /** @var ElementTranslationEntity[] $result */
        $result = $this->getRepository()->findBy(['element' => $elements, 'locale' => $locale]);

        return $result;
    }

    public function save(ElementTranslationEntity $entity, bool $flush = true): void
    {
        $manager = $this->getManager();
        $manager->persist($entity);

        if ($flush) {
            $manager->flush($entity);
        }
    }
}
