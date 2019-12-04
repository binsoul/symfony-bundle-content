<?php

namespace BinSoul\Test\Symfony\Bundle\Content\Entity;

use BinSoul\Symfony\Bundle\Content\Entity\ElementEntity;
use BinSoul\Symfony\Bundle\Content\Entity\ElementRelationEntity;
use BinSoul\Symfony\Bundle\Content\Entity\ElementTranslationEntity;
use PHPUnit\Framework\TestCase;

class ElementEntityTest extends TestCase
{
    public function test_synchronizes_translations(): void
    {
        $element1 = new ElementEntity();

        $translation1 = new ElementTranslationEntity();
        $translation1->setElement($element1);

        $this->assertCount(1, $element1->getTranslations());

        $translation2 = new ElementTranslationEntity();
        $translation2->setElement($element1);

        $this->assertCount(2, $element1->getTranslations());

        $element1->removeTranslation($translation1);
        $this->assertCount(1, $element1->getTranslations());

        $element1->addTranslation($translation1);
        $this->assertCount(2, $element1->getTranslations());

        $element2 = new ElementEntity();
        $translation1->setElement($element2);

        $this->assertCount(1, $element1->getTranslations());
        $this->assertCount(1, $element2->getTranslations());
    }

    public function test_removes_relations(): void
    {
        $parent = new ElementEntity(1);
        $child1 = new ElementEntity(2);
        $child2 = new ElementEntity(2);

        $relation1 = new ElementRelationEntity(1);
        $relation1->setParent($parent);
        $relation1->setChild($child1);

        $relation2 = new ElementRelationEntity(2);
        $relation2->setParent($parent);
        $relation2->setChild($child2);

        $parent->removeRelation($relation1);
        $this->assertCount(1, $parent->getChildren());
        $this->assertCount(0, $child1->getParents());

        $parent->removeRelation($relation2);
        $this->assertCount(0, $parent->getChildren());
        $this->assertCount(0, $child2->getParents());
    }

    public function test_adds_relations(): void
    {
        $parent1 = new ElementEntity(1);
        $child1 = new ElementEntity(3);
        $child2 = new ElementEntity(4);

        $relation1 = new ElementRelationEntity(1);
        $relation1->setParent($parent1);
        $relation1->setChild($child1);

        $relation2 = $this->getMockBuilder(ElementRelationEntity::class)->getMock();
        $relation2->method('getParent')->willReturn($parent1);
        $relation2->method('getChild')->willReturn($child2);

        /* @var ElementRelationEntity $relation2 */
        $parent1->addRelation($relation2);

        $this->assertCount(2, $parent1->getChildren());
        $this->assertCount(1, $child2->getParents());
        $this->assertEquals($parent1, $child2->getParents()[0]->getParent());
    }

    public function test_detects_existing_child_relation(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $parent = new ElementEntity(1);
        $child = new ElementEntity(2);

        $relation1 = $this->getMockBuilder(ElementRelationEntity::class)->getMock();
        $relation1->method('getId')->willReturn(1);
        $relation1->method('getParent')->willReturn($parent);
        $relation1->method('getChild')->willReturn($child);

        $relation2 = $this->getMockBuilder(ElementRelationEntity::class)->getMock();
        $relation1->method('getId')->willReturn(2);
        $relation2->method('getParent')->willReturn($child);
        $relation2->method('getChild')->willReturn($parent);

        /* @var ElementRelationEntity $relation1 */
        $parent->addRelation($relation1);

        /* @var ElementRelationEntity $relation2 */
        $parent->addRelation($relation2);
    }

    public function test_detects_existing_parent_relation(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $parent = new ElementEntity(1);
        $child = new ElementEntity(2);

        $relation1 = $this->getMockBuilder(ElementRelationEntity::class)->getMock();
        $relation1->method('getId')->willReturn(1);
        $relation1->method('getParent')->willReturn($child);
        $relation1->method('getChild')->willReturn($parent);

        $relation2 = $this->getMockBuilder(ElementRelationEntity::class)->getMock();
        $relation1->method('getId')->willReturn(2);
        $relation2->method('getParent')->willReturn($parent);
        $relation2->method('getChild')->willReturn($child);

        /* @var ElementRelationEntity $relation1 */
        $parent->addRelation($relation1);

        /* @var ElementRelationEntity $relation2 */
        $parent->addRelation($relation2);
    }
}
