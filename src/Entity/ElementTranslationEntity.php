<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Entity;

use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a translation of a content element.
 */
#[ORM\Entity]
#[ORM\Table(name: 'element_translation')]
#[ORM\UniqueConstraint(columns: ['element_id', 'locale_id'])]
#[ORM\HasLifecycleCallbacks]
class ElementTranslationEntity
{
    /**
     * @var int|null ID of the translation
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    /**
     * @var ElementEntity Element of the translation
     */
    #[ORM\ManyToOne(targetEntity: ElementEntity::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ElementEntity $element;

    /**
     * @var LocaleEntity Locale of the translation
     */
    #[ORM\ManyToOne(targetEntity: LocaleEntity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private LocaleEntity $locale;

    /**
     * @var string|array Serialized data of the translation
     */
    #[ORM\Column(type: Types::TEXT)]
    private string|array $data;

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

    public function getElement(): ElementEntity
    {
        return $this->element;
    }

    public function setElement(ElementEntity $element): void
    {
        $this->element->removeTranslation($this);

        $element->addTranslation($this);
        $this->element = $element;
    }

    public function getLocale(): LocaleEntity
    {
        return $this->locale;
    }

    public function setLocale(LocaleEntity $locale): void
    {
        $this->locale = $locale;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * Deserializes the data property and return the resulting array.
     */
    public function getStructuredData(): array
    {
        if (! is_string($this->data)) {
            return $this->data;
        }

        return @json_decode($this->data, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Serializes the given array and updates the data property.
     */
    public function setStructuredData(array $data): void
    {
        $this->data = @json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT) ?: '';
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
