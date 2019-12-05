<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element\Renderer;

use BinSoul\Symfony\Bundle\Content\Element\Context;
use BinSoul\Symfony\Bundle\Content\Element\Element;
use BinSoul\Symfony\Bundle\Content\Element\Renderer;
use BinSoul\Symfony\Bundle\Content\Element\Traits\BuilderTrait;
use BinSoul\Symfony\Bundle\Content\Element\Traits\EscaperTrait;

class TagRenderer implements Renderer
{
    use EscaperTrait;
    use BuilderTrait;

    /**
     * @var string[]
     */
    private $tags;

    /**
     * Constructs an instance of this class.
     *
     * @param string[] $tags
     */
    public function __construct(array $tags)
    {
        if (count($tags) === 0) {
            throw new \InvalidArgumentException('At least one tag is required.');
        }

        $this->tags = array_values($tags);
    }

    public function render(Element $element, Context $context): string
    {
        $data = array_merge(['tag' => $this->tags[0], 'content' => ''], $element->getStructuredData());

        $result = '<'.$data['tag'].' class="'.$this->buildClassName($element->getType()).'">';
        $result .= $this->escapeHtml($data['content']);
        $result .= '</'.$data['tag'].'>';

        return $result;
    }
}
