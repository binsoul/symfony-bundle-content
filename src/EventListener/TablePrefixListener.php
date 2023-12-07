<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\EventListener;

use BinSoul\Symfony\Bundle\Doctrine\EventListener\AbstractPrefixListener;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::loadClassMetadata)]
class TablePrefixListener extends AbstractPrefixListener
{
    public function __construct(string $prefix)
    {
        parent::__construct($prefix, 'BinSoul\\Symfony\\Bundle\\Content\\');
    }
}
