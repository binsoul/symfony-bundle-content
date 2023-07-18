<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Entity;

use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a translation of a page.
 */
#[ORM\Table(name: 'page_translation')]
#[ORM\UniqueConstraint(columns: ['page_id', 'locale_id'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class PageTranslationEntity
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
    #[ORM\ManyToOne(targetEntity: PageEntity::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private PageEntity $page;

    /**
     * @var LocaleEntity Locale of the translation
     */
    #[ORM\ManyToOne(targetEntity: LocaleEntity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private LocaleEntity $locale;

    /**
     * @var string Title of the page
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $title;

    /**
     * @var string|null Meta title of the page
     */
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $metaTitle = null;

    /**
     * @var string|null Meta keywords of the page
     */
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $metaKeywords = null;

    /**
     * @var string|null Meta description of the page
     */
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $metaDescription = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTime $createdAt = null;

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
        $this->page->removeTranslation($this);

        $page->addTranslation($this);
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?string $metaKeywords): void
    {
        $this->metaKeywords = $metaKeywords;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt ?? new DateTime();
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt ?? new DateTime();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updatedAt = new DateTime();

        if ($this->createdAt === null) {
            $this->createdAt = new DateTime();
        }
    }
}
