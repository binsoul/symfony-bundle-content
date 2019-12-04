<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element\Type;

use BinSoul\Symfony\Bundle\Content\Element\Context;
use BinSoul\Symfony\Bundle\Content\Element\Element;
use BinSoul\Symfony\Bundle\Content\Element\Renderer;
use BinSoul\Symfony\Bundle\Content\Element\Renderer\RawDataRenderer;
use BinSoul\Symfony\Bundle\Content\Element\Type;

class HtmlType implements Type, Renderer
{
    /**
     * @var RawDataRenderer
     */
    private $renderer;

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
        $result = '<div class="ce-'.$element->getType()->getCode().'">';
        $result .= $element->getRawData();
        $result .= '</div>';

        return $result;
    }
}
