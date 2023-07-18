<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element\Type;

use BinSoul\Symfony\Bundle\Content\Element\Context;
use BinSoul\Symfony\Bundle\Content\Element\Element;
use BinSoul\Symfony\Bundle\Content\Element\Renderer;
use BinSoul\Symfony\Bundle\Content\Element\Traits\BuilderTrait;
use BinSoul\Symfony\Bundle\Content\Element\Type;

class HtmlType implements Type, Renderer
{
    use BuilderTrait;

    public function getCode(): string
    {
        return 'html';
    }

    public function getName(): string
    {
        return 'HTML';
    }

    public function getRenderer(): Renderer
    {
        return $this;
    }

    public function render(Element $element, Context $context): string
    {
        $result = '<div class="' . $this->buildClassName($element->getType()) . '">';
        $result .= $element->getRawData();

        return $result . '</div>';
    }
}
