<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\Annotation\Frujax;
use Ruwork\FrujaxBundle\FrujaxUtils;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxHeaders;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class FrujaxAnnotationListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -256],
        ];
    }

    public function onKernelController(FilterControllerEvent $event): void
    {
        $request = $event->getRequest();

        if (!FrujaxUtils::isFrujaxRequest($request)) {
            return;
        }

        $template = $request->attributes->get('_template');
        $frujax = $request->attributes->get('_ruwork_frujax');
        $block = $request->headers->get(FrujaxHeaders::FRUJAX_BLOCK);

        if (!$template instanceof Template || !$frujax instanceof Frujax || null === $block) {
            return;
        }

        if (!\in_array($block, $frujax->getBlocks(), true)) {
            throw new AccessDeniedHttpException(\sprintf('Rendering of block "%s" is not allowed by the @Frujax annotation.', $block));
        }

        $request->attributes->add([
            '_ruwork_frujax_block' => $block,
            '_ruwork_frujax_template' => $template->getTemplate(),
        ]);

        $template->setTemplate('@RuworkFrujax/frujax_block.html.twig');
    }
}
