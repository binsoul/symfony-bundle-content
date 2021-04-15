<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element;

use InvalidArgumentException;

/**
 * Provides a default implementation of the {@see TypeFactory} interface.
 */
class DefaultTypeFactory implements TypeFactory
{
    /**
     * @var Type[]
     */
    private $types = [];

    /**
     * Constructs an instance of this class.
     *
     * @param Type[]|iterable $types
     */
    public function __construct(iterable $types)
    {
        foreach ($types as $type) {
            $this->types[$type->getCode()] = $type;
        }
    }

    public function all(): array
    {
        return array_values($this->types);
    }

    public function build(string $code): Type
    {
        if (isset($this->types[$code])) {
            return $this->types[$code];
        }

        throw new InvalidArgumentException(sprintf('Unknown element type "%s".', $code));
    }
}
