<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element\Renderer;

use BinSoul\Symfony\Bundle\Content\Element\Context;
use BinSoul\Symfony\Bundle\Content\Element\Element;
use BinSoul\Symfony\Bundle\Content\Element\Renderer;

class RawDataRenderer implements Renderer
{
    public function render(Element $element, Context $context): string
    {
        $result = '<div class="ce-'.$element->getType()->getCode().'">';
        $result .= htmlspecialchars($element->getRawData(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $result .= '</div>';

        return $result;
    }
}
