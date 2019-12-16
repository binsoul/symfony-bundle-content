<?php

namespace BinSoul\Symfony\Bundle\Content\EventListener;

use BinSoul\Symfony\Bundle\Content\Entity\PageTranslationEntity;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

/**
 * Synchronizes the translations property of page entities.
 */
class SynchronizePageTranslationListener implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [Events::preRemove];
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if ($entity instanceof PageTranslationEntity) {
            $entity->getPage()->removeTranslation($entity);
        }
    }
}
