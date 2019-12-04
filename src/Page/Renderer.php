<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Page;

use BinSoul\Symfony\Bundle\Content\Element\Context;
use BinSoul\Symfony\Bundle\Content\Entity\PageEntity;
use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;

/**
 * Renders pages.
 */
interface Renderer
{
    public function render(PageEntity $page, LocaleEntity $locale, Context $context): string;
}
