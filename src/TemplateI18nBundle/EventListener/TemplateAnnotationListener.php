<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\EventListener;

use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class TemplateAnnotationListener implements EventSubscriberInterface
{
    private $resolver;

    public function __construct(LocalizedTemplateResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 10],
        ];
    }

    public function onKernelView(GetResponseForControllerResultEvent $event): void
    {
        $config = $event->getRequest()
            ->attributes
            ->get('_template');

        if (!$config instanceof Template) {
            return;
        }

        $template = $this->resolver->resolve($config->getTemplate());

        $config->setTemplate($template);
    }
}
