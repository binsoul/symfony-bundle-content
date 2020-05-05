<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a content element.
 *
 * @ORM\Entity()
 * @ORM\Table(name="element")
 * @ORM\HasLifecycleCallbacks()
 */
class ElementEntity
{
    /**
     * @var int|null ID of the route
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string Type of the the element
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private $type;

    /**
     * @var string|null Name of the the element
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $name;

    /**
     * @var bool Visibility of the element
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isVisible;

    /**
     * @var \DateTime|null Start of the visibility of the element
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $visibleFrom;

    /**
     * @var \DateTime|null End of the visibility of the element
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $visibleTo;

    /**
     * @var string|null Margin before the element
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $marginBefore;

    /**
     * @var string|null Margin after the element
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $marginAfter;

    /**
     * @var \DateTime Update date of the element
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \DateTime Creation date of the element
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var ElementTranslationEntity[]|Collection<int, ElementTranslationEntity>
     * @ORM\OneToMany(targetEntity="\BinSoul\Symfony\Bundle\Content\Entity\ElementTranslationEntity", mappedBy="element")
     */
    private $translations;

    /**
     * @var ElementRelationEntity[]|Collection<int, ElementRelationEntity>
     * @ORM\OneToMany(targetEntity="\BinSoul\Symfony\Bundle\Content\Entity\ElementRelationEntity", mappedBy="parent")
     */
    private $children;

    /**
     * @var ElementRelationEntity[]|Collection<int, ElementRelationEntity>
     * @ORM\OneToMany(targetEntity="\BinSoul\Symfony\Bundle\Content\Entity\ElementRelationEntity", mappedBy="child")
     */
    private $parents;

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

    public function getVisibleFrom(): ?\DateTimeInterface
    {
        return $this->visibleFrom;
    }

    public function setVisibleFrom(?\DateTime $visibleFrom): void
    {
        $this->visibleFrom = $visibleFrom;
    }

    public function getVisibleTo(): ?\DateTimeInterface
    {
        return $this->visibleTo;
    }

    public function setVisibleTo(?\DateTime $visibleTo): void
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

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt ?? new \DateTime();
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt ?? new \DateTime();
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
                    throw new \InvalidArgumentException('Relationship already exists as parent of this element.');
                }
            }

            if (! $this->children->contains($relation)) {
                $this->children->add($relation);
                $relation->getChild()->addRelation($relation);
            }
        } elseif ($relation->getChild()->getId() === $this->getId()) {
            foreach ($this->children as $entity) {
                if ($entity->getChild()->getId() === $relation->getParent()->getId()) {
                    throw new \InvalidArgumentException('Relationship already exists as child of this element.');
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

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTime();

        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime();
        }
    }
}
