<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element;

interface Type
{
    /**
     * Returns the code of the element type.
     */
    public function getCode(): string;

    /**
     * Returns the name of the element type.
     */
    public function getName(): string;

    /**
     * Returns a renderer for elements of this type.
     */
    public function getRenderer(): Renderer;
}
