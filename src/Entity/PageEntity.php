<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Entity;

use BinSoul\Symfony\Bundle\Routing\Entity\RouteEntity;
use BinSoul\Symfony\Bundle\Website\Entity\WebsiteEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a web page.
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="page",
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(columns={"route_id"}),
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class PageEntity
{
    /**
     * @var int|null ID of the route
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var WebsiteEntity Website of the page
     * @ORM\ManyToOne(targetEntity="\BinSoul\Symfony\Bundle\Website\Entity\WebsiteEntity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $website;

    /**
     * @var RouteEntity Canonical route of the page
     * @ORM\ManyToOne(targetEntity="\BinSoul\Symfony\Bundle\Routing\Entity\RouteEntity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $route;

    /**
     * @var string Name of the page
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime Update date of the product
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \DateTime Creation date of the product
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var PageTranslationEntity[]|Collection<int, PageTranslationEntity>
     * @ORM\OneToMany(targetEntity="\BinSoul\Symfony\Bundle\Content\Entity\PageTranslationEntity", mappedBy="page")
     */
    private $translations;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(?int $id = null)
    {
        $this->id = $id;
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebsite(): WebsiteEntity
    {
        return $this->website;
    }

    public function setWebsite(WebsiteEntity $website): void
    {
        $this->website = $website;
    }

    public function getRoute(): RouteEntity
    {
        return $this->route;
    }

    public function setRoute(RouteEntity $route): void
    {
        $this->route = $route;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt ?? new \DateTime();
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt ?? new \DateTime();
    }

    /**
     * @return PageTranslationEntity[]
     */
    public function getTranslations(): array
    {
        return $this->translations->toArray();
    }

    /*
     * Adds a translation for this page.
     */
    public function addTranslation(PageTranslationEntity $translation): void
    {
        if (! $this->translations->contains($translation)) {
            $this->translations->add($translation);
        }
    }

    /*
     * Removes a translation for this page.
     */
    public function removeTranslation(PageTranslationEntity $translation): void
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
        }
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTime();

        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime();
        }
    }
}
