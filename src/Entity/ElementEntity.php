<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Represents a content element.
 */
#[ORM\Entity]
#[ORM\Table(name: 'element')]
#[ORM\HasLifecycleCallbacks]

class ElementEntity
{
    /**
     * @var int|null ID of the route
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    /**
     * @var string Type of the the element
     */
    #[ORM\Column(type: Types::STRING, length: 128)]
    private string $type;

    /**
     * @var string|null Name of the the element
     */
    #[ORM\Column(type: Types::STRING, length: 128, nullable: true)]
    private ?string $name = null;

    /**
     * @var bool Visibility of the element
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isVisible = false;

    /**
     * @var DateTimeInterface|null Start of the visibility of the element
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $visibleFrom;

    /**
     * @var DateTimeInterface|null End of the visibility of the element
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $visibleTo;

    /**
     * @var string|null Margin before the element
     */
    #[ORM\Column(type: Types::STRING, length: 32, nullable: true)]
    private ?string $marginBefore;

    /**
     * @var string|null Margin after the element
     */
    #[ORM\Column(type: Types::STRING, length: 32, nullable: true)]
    private ?string $marginAfter;

    /**
     * @var DateTimeInterface Update date of the element
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    /**
     * @var DateTimeInterface Creation date of the element
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    /**
     * @var ElementTranslationEntity[]|Collection<int, ElementTranslationEntity>
     */
    #[ORM\OneToMany(mappedBy: 'element', targetEntity: ElementTranslationEntity::class)]
    private Collection $translations;

    /**
     * @var ElementRelationEntity[]|Collection<int, ElementRelationEntity>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: ElementRelationEntity::class)]
    private Collection $children;

    /**
     * @var ElementRelationEntity[]|Collection<int, ElementRelationEntity>
     */
    #[ORM\OneToMany(mappedBy: 'child', targetEntity: ElementRelationEntity::class)]
    private collection $parents;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(?int $id = null)
    {
        $this->id = $id;
        $this->translations = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->parents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): void
    {
        $this->isVisible = $isVisible;
    }

    public function getVisibleFrom(): ?DateTimeInterface
    {
        return $this->visibleFrom;
    }

    public function setVisibleFrom(?DateTimeInterface $visibleFrom): void
    {
        $this->visibleFrom = $visibleFrom;
    }

    public function getVisibleTo(): ?DateTimeInterface
    {
        return $this->visibleTo;
    }

    public function setVisibleTo(?DateTimeInterface $visibleTo): void
    {
        $this->visibleTo = $visibleTo;
    }

    public function getMarginBefore(): ?string
    {
        return $this->marginBefore;
    }

    public function setMarginBefore(?string $marginBefore): void
    {
        $this->marginBefore = $marginBefore;
    }

    public function getMarginAfter(): ?string
    {
        return $this->marginAfter;
    }

    public function setMarginAfter(?string $marginAfter): void
    {
        $this->marginAfter = $marginAfter;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt ?? new DateTime();
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt ?? new DateTime();
    }

    /**
     * @return ElementTranslationEntity[]
     */
    public function getTranslations(): array
    {
        return $this->translations->toArray();
    }

    /*
     * Adds a translation for this element.
     */
    public function addTranslation(ElementTranslationEntity $translation): void
    {
        if (! $this->translations->contains($translation)) {
            $this->translations->add($translation);
        }
    }

    /*
     * Removes a translation for this element.
     */
    public function removeTranslation(ElementTranslationEntity $translation): void
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
        }
    }

    /**
     * Returns all a relations where this element is a parent.
     *
     * @return ElementRelationEntity[]
     */
    public function getChildren(): array
    {
        return $this->children->toArray();
    }

    /**
     * Returns all a relations where this element is a child.
     *
     * @return ElementRelationEntity[]
     */
    public function getParents(): array
    {
        return $this->parents->toArray();
    }

    /**
     * Returns all a relations for this element.
     *
     * @return ElementRelationEntity[]
     */
    public function getRelations(): array
    {
        return array_merge($this->parents->toArray(), $this->children->toArray());
    }

    /*
     * Adds a relation for this element.
     */
    public function addRelation(ElementRelationEntity $relation): void
    {
        if ($relation->getParent()->getId() === $this->id) {
            foreach ($this->parents as $entity) {
                if ($entity->getParent()->getId() === $relation->getChild()->getId()) {
                    throw new InvalidArgumentException('Relationship already exists as parent of this element.');
                }
            }

            if (! $this->children->contains($relation)) {
                $this->children->add($relation);
                $relation->getChild()->addRelation($relation);
            }
        } elseif ($relation->getChild()->getId() === $this->getId()) {
            foreach ($this->children as $entity) {
                if ($entity->getChild()->getId() === $relation->getParent()->getId()) {
                    throw new InvalidArgumentException('Relationship already exists as child of this element.');
                }
            }

            if (! $this->parents->contains($relation)) {
                $this->parents->add($relation);
                $relation->getParent()->addRelation($relation);
            }
        }
    }

    /*
     * Removes a relation for this element.
     */
    public function removeRelation(ElementRelationEntity $element): void
    {
        if ($this->parents->contains($element)) {
            $this->parents->removeElement($element);
            $element->getParent()->removeRelation($element);
        }

        if ($this->children->contains($element)) {
            $this->children->removeElement($element);
            $element->getChild()->removeRelation($element);
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updatedAt = new DateTime();

        if ($this->createdAt === null) {
            $this->createdAt = new DateTime();
        }
    }
}
