<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element;

/**
 * Renders elements.
 */
interface Renderer
{
    /**
     * Renders HTML for the given element in the given context.
     */
    public function render(Element $element, Context $context): string;
}
