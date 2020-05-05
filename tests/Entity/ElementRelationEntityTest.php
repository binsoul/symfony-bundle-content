<?php

declare(strict_types=1);

namespace BinSoul\Test\Symfony\Bundle\Content\Entity;

use BinSoul\Symfony\Bundle\Content\Entity\ElementEntity;
use BinSoul\Symfony\Bundle\Content\Entity\ElementRelationEntity;
use PHPUnit\Framework\TestCase;

class ElementRelationEntityTest extends TestCase
{
    public function test_synchronizes_relations(): void
    {
        $parent = new ElementEntity(1);
        $child = new ElementEntity(2);

        $relation1 = new ElementRelationEntity();
        $relation1->setParent($parent);
        $relation1->setChild($child);

        $this->assertCount(1, $parent->getChildren());
        $this->assertCount(1, $child->getParents());

        $relation2 = new ElementRelationEntity();
        $relation2->setChild($child);
        $relation2->setParent($parent);

        $this->assertCount(2, $parent->getChildren());
        $this->assertCount(2, $child->getParents());
    }

    public function test_updates_relations(): void
    {
        $parent1 = new ElementEntity(1);
        $parent2 = new ElementEntity(2);
        $child1 = new ElementEntity(3);
        $child2 = new ElementEntity(4);

        $relation1 = new ElementRelationEntity();
        $relation1->setParent($parent1);
        $relation1->setChild($child1);
        $relation1->setChild($child2);

        $this->assertCount(1, $parent1->getChildren());
        $this->assertCount(0, $parent2->getChildren());
        $this->assertCount(0, $child1->getParents());
        $this->assertCount(1, $child2->getParents());

        $relation1->setParent($parent2);

        $this->assertCount(0, $parent1->getChildren());
        $this->assertCount(1, $parent2->getChildren());
        $this->assertCount(0, $child1->getParents());
        $this->assertCount(1, $child2->getParents());
    }

    public function test_detects_same_entities_child(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $element = new ElementEntity(1);
        $relation1 = new ElementRelationEntity();
        $relation1->setParent($element);
        $relation1->setChild($element);
    }

    public function test_detects_same_entities_parent(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $element = new ElementEntity(1);
        $relation1 = new ElementRelationEntity();
        $relation1->setChild($element);
        $relation1->setParent($element);
    }
}
