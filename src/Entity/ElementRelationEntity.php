<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Represents a relationship between content elements.
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="element_relation",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"parent_id", "child_id"}),
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class ElementRelationEntity
{
    /**
     * @var int|null ID of the translation
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var ElementEntity Element of the translation
     * @ORM\ManyToOne(targetEntity="\BinSoul\Symfony\Bundle\Content\Entity\ElementEntity", inversedBy="children")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var ElementEntity Element of the translation
     * @ORM\ManyToOne(targetEntity="\BinSoul\Symfony\Bundle\Content\Entity\ElementEntity", inversedBy="parents")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $child;

    /**
     * @var int Sort order of the child
     * @ORM\Column(type="integer", nullable=false)
     */
    private $sortOrder;

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
        if ($this->child !== null && $this->child->getId() === $parent->getId()) {
            throw new InvalidArgumentException(sprintf('Parent and child should not be equal.'));
        }

        if ($this->parent !== null) {
            $this->parent->removeRelation($this);
        }

        $this->parent = $parent;

        if ($this->child !== null) {
            $parent->addRelation($this);
            $this->child->addRelation($this);
        }
    }

    public function getChild(): ElementEntity
    {
        return $this->child;
    }

    public function setChild(ElementEntity $child): void
    {
        if ($this->parent !== null && $this->parent->getId() === $child->getId()) {
            throw new InvalidArgumentException(sprintf('Parent and child should not be equal.'));
        }

        if ($this->child !== null) {
            $this->child->removeRelation($this);
        }

        $this->child = $child;

        if ($this->parent !== null) {
            $child->addRelation($this);
            $this->parent->addRelation($this);
        }
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
