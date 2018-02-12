<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\EventListener;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

final class I18nControllerTemplateListener implements EventSubscriberInterface
{
    private $twig;
    private $locales;
    private $defaultLocale;

    public function __construct(Environment $twig, array $locales, string $defaultLocale)
    {
        $this->twig = $twig;
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -140],
        ];
    }

    public function onKernelController(FilterControllerEvent $event): void
    {
        $request = $event->getRequest();
        $locale = $request->getLocale();

        if ($this->defaultLocale === $locale || !in_array($locale, $this->locales, true)) {
            return;
        }

        /* @var Template $config */
        $config = $request->attributes->get('_template');

        if (!$config instanceof Template) {
            return;
        }

        $i18nTemplate = preg_replace('/(\.\w+\.twig)$/', '.'.$locale.'$1', (string) $config->getTemplate());

        if ($this->twig->getLoader()->exists($i18nTemplate)) {
            $config->setTemplate($i18nTemplate);
        }
    }
}
