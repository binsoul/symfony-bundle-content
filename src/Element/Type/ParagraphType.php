<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element\Type;

use BinSoul\Symfony\Bundle\Content\Element\Renderer;
use BinSoul\Symfony\Bundle\Content\Element\Renderer\TagRenderer;
use BinSoul\Symfony\Bundle\Content\Element\Type;

class ParagraphType implements Type
{
    private ?TagRenderer $renderer = null;

    public function getCode(): string
    {
        return 'paragraph';
    }

    public function getName(): string
    {
        return 'Paragraph';
    }

    public function getRenderer(): Renderer
    {
        if ($this->renderer === null) {
            $this->renderer = new TagRenderer(['p', 'div']);
        }

        return $this->renderer;
    }
}
