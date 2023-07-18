<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element\Type;

use BinSoul\Symfony\Bundle\Content\Element\Renderer;
use BinSoul\Symfony\Bundle\Content\Element\Renderer\TagRenderer;
use BinSoul\Symfony\Bundle\Content\Element\Type;

class HeadingType implements Type
{
    private ?TagRenderer $renderer = null;

    public function getCode(): string
    {
        return 'heading';
    }

    public function getName(): string
    {
        return 'Heading';
    }

    public function getRenderer(): Renderer
    {
        if ($this->renderer === null) {
            $this->renderer = new TagRenderer(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span']);
        }

        return $this->renderer;
    }
}
