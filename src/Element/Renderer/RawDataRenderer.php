<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element\Renderer;

use BinSoul\Symfony\Bundle\Content\Element\Context;
use BinSoul\Symfony\Bundle\Content\Element\Element;
use BinSoul\Symfony\Bundle\Content\Element\Renderer;
use BinSoul\Symfony\Bundle\Content\Element\Traits\BuilderTrait;
use BinSoul\Symfony\Bundle\Content\Element\Traits\EscaperTrait;

class RawDataRenderer implements Renderer
{
    use EscaperTrait;
    use BuilderTrait;

    public function render(Element $element, Context $context): string
    {
        $result = '<div class="'.$this->buildClassName($element->getType()).'">';
        $result .= $this->escapeHtml($element->getRawData());
        $result .= '</div>';

        return $result;
    }
}
