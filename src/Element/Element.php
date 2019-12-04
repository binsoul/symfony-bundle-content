<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element;

interface Element
{
    /**
     * Returns the id of this element.
     */
    public function getId(): int;

    /**
     * Returns the type of this element.
     */
    public function getType(): Type;

    /**
     * Returns the data stored in the database.
     */
    public function getRawData(): string;

    /**
     * Returns the deserialized data.
     *
     * @return mixed[]
     */
    public function getStructuredData(): array;
}
