<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Entity;

use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Links an element to a page.
 */
#[ORM\Entity]
#[ORM\Table(name: 'page_element')]
#[ORM\Index(columns: ['page_id', 'locale_id'])]
#[ORM\UniqueConstraint(columns: ['page_id', 'locale_id', 'element_id'])]
#[ORM\Cache(usage: 'NONSTRICT_READ_WRITE')]
class PageElementEntity
{
    /**
     * @var int|null ID of the translation
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    /**
     * @var PageEntity Page of the translation
     */
    #[ORM\ManyToOne(targetEntity: PageEntity::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private PageEntity $page;

    /**
     * @var LocaleEntity Locale of the translation
     */
    #[ORM\ManyToOne(targetEntity: LocaleEntity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private LocaleEntity $locale;

    /**
     * @var ElementEntity Element of the translation
     */
    #[ORM\ManyToOne(targetEntity: ElementEntity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ElementEntity $element;

    /**
     * @var int Sort order of the child
     */
    #[ORM\Column(type: Types::INTEGER)]
    private int $sortOrder;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPage(): PageEntity
    {
        return $this->page;
    }

    public function setPage(PageEntity $page): void
    {
        $this->page = $page;
    }

    public function getLocale(): LocaleEntity
    {
        return $this->locale;
    }

    public function setLocale(LocaleEntity $locale): void
    {
        $this->locale = $locale;
    }

    public function getElement(): ElementEntity
    {
        return $this->element;
    }

    public function setElement(ElementEntity $element): void
    {
        $this->element = $element;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }
}
