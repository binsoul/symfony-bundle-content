<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\EventListener;

use BinSoul\Symfony\Bundle\Content\Entity\ElementRelationEntity;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;

/**
 * Synchronizes the parents and children of element entities.
 */
#[AsDoctrineListener(event: Events::preRemove)]
class SynchronizeElementRelationListener
{
    public function preRemove(PreRemoveEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof ElementRelationEntity) {
            $entity->getParent()->removeRelation($entity);
            $entity->getChild()->removeRelation($entity);
        }
    }
}
