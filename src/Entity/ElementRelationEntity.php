<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Represents a relationship between content elements.
 */
#[ORM\Entity]
#[ORM\Table(name: 'element_relation')]
#[ORM\UniqueConstraint(columns: ['parent_id', 'child_id'])]
#[ORM\HasLifecycleCallbacks]
class ElementRelationEntity
{
    /**
     * @var int|null ID of the translation
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    /**
     * @var ElementEntity Element of the translation
     */
    #[ORM\ManyToOne(targetEntity: ElementEntity::class, inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ElementEntity $parent;

    /**
     * @var ElementEntity Element of the translation
     */
    #[ORM\ManyToOne(targetEntity: ElementEntity::class, inversedBy: 'parents')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ElementEntity $child;

    /**
     * @var int Sort order of the child
     */
    #[ORM\Column(type: Types::INTEGER)]
    private int $sortOrder;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ElementEntity
    {
        return $this->parent;
    }

    public function setParent(ElementEntity $parent): void
    {
        if ($this->child->getId() === $parent->getId()) {
            throw new InvalidArgumentException('Parent and child should not be equal.');
        }

        $this->parent->removeRelation($this);

        $this->parent = $parent;
        $parent->addRelation($this);
        $this->child->addRelation($this);
    }

    public function getChild(): ElementEntity
    {
        return $this->child;
    }

    public function setChild(ElementEntity $child): void
    {
        if ($this->parent->getId() === $child->getId()) {
            throw new InvalidArgumentException('Parent and child should not be equal.');
        }

        $this->child->removeRelation($this);

        $this->child = $child;
        $child->addRelation($this);
        $this->parent->addRelation($this);
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }
}
