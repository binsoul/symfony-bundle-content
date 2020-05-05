<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Entity;

use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a translation of a content element.
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="element_translation",
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(columns={"element_id", "locale_id"}),
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class ElementTranslationEntity
{
    /**
     * @var int|null ID of the translation
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var ElementEntity Element of the translation
     * @ORM\ManyToOne(targetEntity="\BinSoul\Symfony\Bundle\Content\Entity\ElementEntity", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $element;

    /**
     * @var LocaleEntity Locale of the translation
     * @ORM\ManyToOne(targetEntity="\BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locale;

    /**
     * @var string Serialized data of the translation
     * @ORM\Column(type="text", nullable=false)
     */
    private $data;

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
        if ($this->element !== null) {
            $this->element->removeTranslation($this);
        }

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
     *
     * @return mixed[]
     */
    public function getStructuredData(): array
    {
        if ($this->data === null) {
            return [];
        }

        if (! \is_string($this->data)) {
            return (array) $this->data;
        }

        return @json_decode($this->data, true);
    }

    /**
     * Serializes the given array and updates the data property.
     *
     * @param mixed[] $data
     */
    public function setStructuredData(array $data): void
    {
        $this->data = @json_encode($data, JSON_PRETTY_PRINT) ?: '';
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
