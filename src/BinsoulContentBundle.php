<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BinsoulContentBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(
            $this->buildDoctrineOrmMappingsPass(
                ['BinSoul\Symfony\Bundle\Content'],
                [(string) realpath(__DIR__ . '/Entity')],
            )
        );
    }

    private function buildDoctrineOrmMappingsPass(array $namespaces, array $directories): DoctrineOrmMappingsPass
    {
        $driver = new Definition(AttributeDriver::class, [$directories]);

        return new DoctrineOrmMappingsPass($driver, $namespaces, [], false, []);
    }
}
