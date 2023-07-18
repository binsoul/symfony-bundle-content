<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element\Type;

use BinSoul\Symfony\Bundle\Content\Element\Renderer;
use BinSoul\Symfony\Bundle\Content\Element\Renderer\RawDataRenderer;
use BinSoul\Symfony\Bundle\Content\Element\Type;

class TextType implements Type
{
    private ?RawDataRenderer $renderer = null;

    public function getCode(): string
    {
        return 'text';
    }

    public function getName(): string
    {
        return 'Text';
    }

    public function getRenderer(): Renderer
    {
        if ($this->renderer === null) {
            $this->renderer = new RawDataRenderer();
        }

        return $this->renderer;
    }
}
