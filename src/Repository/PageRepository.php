<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Repository;

use BinSoul\Symfony\Bundle\Content\Entity\PageEntity;
use BinSoul\Symfony\Bundle\Doctrine\Repository\AbstractRepository;
use BinSoul\Symfony\Bundle\Routing\Entity\RouteEntity;
use BinSoul\Symfony\Bundle\Website\Entity\WebsiteEntity;
use Doctrine\Persistence\ManagerRegistry;

class PageRepository extends AbstractRepository
{
    /**
     * Constructs an instance of this class.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(PageEntity::class, $registry);
    }

    /**
     * @return PageEntity[]
     */
    public function loadAll(): array
    {
        /** @var PageEntity[] $result */
        $result = $this->getRepository()->findBy([]);

        return $result;
    }

    public function load(int $id): ?PageEntity
    {
        /** @var PageEntity|null $result */
        $result = $this->getRepository()->find($id);

        return $result;
    }

    /**
     * @return PageEntity[]
     */
    public function findAllByWebsite(WebsiteEntity $website): array
    {
        return $this->getRepository()->findBy(['website' => $website]);
    }

    public function findByRoute(RouteEntity $route): ?PageEntity
    {
        /** @var PageEntity|null $result */
        $result = $this->getRepository()->findOneBy(['route' => $route]);

        return $result;
    }

    public function save(PageEntity $entity, bool $flush = true): void
    {
        $manager = $this->getManager();
        $manager->persist($entity);

        if ($flush) {
            $manager->flush($entity);
        }
    }
}
