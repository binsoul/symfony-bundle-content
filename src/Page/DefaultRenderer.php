<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Page;

use BinSoul\Symfony\Bundle\Content\Element\Context;
use BinSoul\Symfony\Bundle\Content\Element\DefaultElement;
use BinSoul\Symfony\Bundle\Content\Element\TypeFactory;
use BinSoul\Symfony\Bundle\Content\Entity\ElementEntity;
use BinSoul\Symfony\Bundle\Content\Entity\PageElementEntity;
use BinSoul\Symfony\Bundle\Content\Entity\PageEntity;
use BinSoul\Symfony\Bundle\Content\Repository\ElementTranslationRepository;
use BinSoul\Symfony\Bundle\Content\Repository\PageElementRepository;
use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;

/**
 * Provides a default implementation of the {@see \BinSoul\Symfony\Bundle\Content\Page\Renderer Renderer} interface.
 */
class DefaultRenderer implements Renderer
{
    /**
     * @var PageElementRepository
     */
    private $pageElementRepository;

    /**
     * @var ElementTranslationRepository
     */
    private $elementTranslationRepository;

    /**
     * @var TypeFactory
     */
    private $typeFactory;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(
        PageElementRepository $pageElementRepository,
        ElementTranslationRepository $elementTranslationRepository,
        TypeFactory $typeFactory
    ) {
        $this->pageElementRepository = $pageElementRepository;
        $this->elementTranslationRepository = $elementTranslationRepository;
        $this->typeFactory = $typeFactory;
    }

    public function render(PageEntity $page, LocaleEntity $locale, Context $context): string
    {
        $pageElements = $this->pageElementRepository->findAllByPageAndLocale($page, $locale);
        uasort(
            $pageElements,
            static function (PageElementEntity $a, PageElementEntity $b) {
                return $a->getSortOrder() <=> $b->getSortOrder();
            }
        );

        /** @var ElementEntity[] $elements */
        $elements = [];

        foreach ($pageElements as $pageElement) {
            $elements[] = $pageElement->getElement();
        }

        $context->set('page', $page);
        $context->set('locale', $locale);

        $elementTranslations = $this->elementTranslationRepository->findAllByElementsAndLocale($elements, $locale);

        $result = '';

        foreach ($elements as $element) {
            if (! $element->isVisible()) {
                continue;
            }

            $translation = null;

            foreach ($elementTranslations as $elementTranslation) {
                if ($elementTranslation->getElement()->getId() === $element->getId()) {
                    $translation = $elementTranslation;

                    break;
                }
            }

            if ($translation === null) {
                continue;
            }

            $type = $this->typeFactory->build($element->getType());
            $renderer = $type->getRenderer();

            $result .= $renderer->render(new DefaultElement($type, $element, $translation), $context);
        }

        return $result;
    }
}
