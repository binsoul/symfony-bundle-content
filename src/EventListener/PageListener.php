<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\EventListener;

use BinSoul\Symfony\Bundle\Content\Repository\PageRepository;
use BinSoul\Symfony\Bundle\Content\Repository\PageTranslationRepository;
use BinSoul\Symfony\Bundle\I18n\Entity\LocaleEntity;
use BinSoul\Symfony\Bundle\Routing\Entity\RouteEntity;
use BinSoul\Symfony\Bundle\Website\Entity\DomainEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PageListener implements EventSubscriberInterface
{
    /**
     * @var PageRepository
     */
    private $pageRepository;
    /**
     * @var PageTranslationRepository
     */
    private $pageTranslationRepository;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(PageRepository $pageRepository, PageTranslationRepository $pageTranslationRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->pageTranslationRepository = $pageTranslationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [
                ['onController', -140],
            ],
        ];
    }

    public function onController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        /** @var DomainEntity|null $domain */
        $domain = $request->attributes->get('domain');
        if (!$domain) {
            return;
        }

        $request->attributes->set('website', $domain->getWebsite());

        /** @var LocaleEntity $locale */
        $locale = $request->attributes->get('locale', $domain->getDefaultLocale() ?? $domain->getWebsite()->getDefaultLocale());
        $request->attributes->set('locale', $locale);
        $request->attributes->set('language', $locale->getLanguage());

        $page = null;
        $pageDescription = null;
        /** @var RouteEntity|null $route */
        $route = $request->attributes->get('route');
        if ($route) {
            $page = $this->pageRepository->findByRoute($route);
            if ($page) {
                foreach ([$locale, $domain->getDefaultLocale(), $domain->getWebsite()->getDefaultLocale()] as $translationLocale) {
                    if ($translationLocale === null) {
                        continue;
                    }

                    $pageDescription = $this->pageTranslationRepository->findByPageAndLocale($page, $translationLocale);
                    if ($pageDescription) {
                        break;
                    }
                }
            }
        }

        $request->attributes->set('page', $page);
        $request->attributes->set('pageDescription', $pageDescription);
    }
}
