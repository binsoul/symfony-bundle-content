<?php

namespace BinSoul\Symfony\Bundle\Content\EventListener;

use BinSoul\Symfony\Bundle\Content\Entity\ElementTranslationEntity;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

/**
 * Synchronizes the translations property of element entities.
 */
class SynchronizeElementTranslationListener implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [Events::preRemove];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof ElementTranslationEntity) {
            $entity->getElement()->removeTranslation($entity);
        }
    }
}
