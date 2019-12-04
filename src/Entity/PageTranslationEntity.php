<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Entity;

use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a translation of a page.
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="page_translation",
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(columns={"page_id", "locale_id"}),
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class PageTranslationEntity
{
    /**
     * @var int|null ID of the translation
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var PageEntity Page of the translation
     * @ORM\ManyToOne(targetEntity="\BinSoul\Symfony\Bundle\Content\Entity\PageEntity", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $page;

    /**
     * @var LocaleEntity Locale of the translation
     * @ORM\ManyToOne(targetEntity="\BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locale;

    /**
     * @var string Title of the page
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string|null Meta title of the page
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaTitle;

    /**
     * @var string|null Meta keywords of the page
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaKeywords = '';

    /**
     * @var string|null Meta description of the page
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaDescription = '';

    /**
     * @var \DateTime Update date of the translation
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \DateTime Creation date of the translation
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

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

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt ?? new \DateTime();
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt ?? new \DateTime();
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
