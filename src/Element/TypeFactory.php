<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element;

/**
 * Builds element types.
 */
interface TypeFactory
{
    /**
     * Returns all registered types.
     *
     * @return Type[]
     */
    public function all(): array;

    /**
     * Builds a type for the given code.
     */
    public function build(string $code): Type;
}
