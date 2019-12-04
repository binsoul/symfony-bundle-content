<?php

namespace BinSoul\Symfony\Bundle\Content\EventListener;

use BinSoul\Symfony\Bundle\Content\Entity\ElementRelationEntity;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

/**
 * Synchronizes the parents and children of element entities.
 */
class SynchronizeElementRelationListener implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [Events::preRemove];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof ElementRelationEntity) {
            $entity->getParent()->removeRelation($entity);
            $entity->getChild()->removeRelation($entity);
        }
    }
}
