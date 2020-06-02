<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigGlobalsListener implements EventSubscriberInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return mixed[][]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [
                ['onController', -150],
            ],
        ];
    }

    public function onController(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        $this->twig->addGlobal('domain', $request->attributes->get('domain'));
        $this->twig->addGlobal('website', $request->attributes->get('website'));
        $this->twig->addGlobal('locale', $request->attributes->get('locale'));
        $this->twig->addGlobal('language', $request->attributes->get('language'));
        $this->twig->addGlobal('page', $request->attributes->get('page'));
        $this->twig->addGlobal('pageDescription', $request->attributes->get('pageDescription'));
    }
}
