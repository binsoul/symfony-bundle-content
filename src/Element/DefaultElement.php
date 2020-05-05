<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element;

use BinSoul\Symfony\Bundle\Content\Entity\ElementEntity;
use BinSoul\Symfony\Bundle\Content\Entity\ElementTranslationEntity;

/**
 * Provides a default implementation of the {@see Element} interface.
 */
class DefaultElement implements Element
{
    /**
     * @var ElementEntity
     */
    private $elementEntity;

    /**
     * @var ElementTranslationEntity
     */
    private $elementTranslationEntity;

    /**
     * @var Type
     */
    private $type;

    /**
     * Constructs an instance of ths class.
     */
    public function __construct(Type $type, ElementEntity $elementEntity, ElementTranslationEntity $elementTranslationEntity)
    {
        $this->type = $type;
        $this->elementEntity = $elementEntity;
        $this->elementTranslationEntity = $elementTranslationEntity;
    }

    public function getId(): int
    {
        return $this->elementEntity->getId() ?? 0;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getRawData(): string
    {
        return $this->elementTranslationEntity->getData();
    }

    public function getStructuredData(): array
    {
        return $this->elementTranslationEntity->getStructuredData();
    }
}
