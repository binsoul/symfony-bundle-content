<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\EventListener;

use BinSoul\Symfony\Bundle\Content\Entity\PageTranslationEntity;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;

/**
 * Synchronizes the translations property of page entities.
 */
class SynchronizePageTranslationListener implements EventSubscriber
{
    /**
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [Events::preRemove];
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof PageTranslationEntity) {
            $entity->getPage()->removeTranslation($entity);
        }
    }
}
