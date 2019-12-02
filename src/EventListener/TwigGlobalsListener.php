<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TwigGlobalsListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
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

        if (!$this->container->has('twig')) {
            return;
        }

        /** @var \Twig\Environment $twig */
        $twig = $this->container->get('twig');

        $twig->addGlobal('domain', $request->attributes->get('domain'));
        $twig->addGlobal('website', $request->attributes->get('website'));
        $twig->addGlobal('locale', $request->attributes->get('locale'));
        $twig->addGlobal('language', $request->attributes->get('language'));
        $twig->addGlobal('page', $request->attributes->get('page'));
        $twig->addGlobal('pageDescription', $request->attributes->get('pageDescription'));
    }
}
