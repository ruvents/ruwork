<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\Annotation\Frujax;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxHeaders;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxRequestChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class FrujaxTemplateListener implements EventSubscriberInterface
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

        if (!FrujaxRequestChecker::isFrujaxRequest($request)) {
            return;
        }

        $template = $request->attributes->get('_template');
        $frujax = $request->attributes->get('_frujax');
        $block = $request->headers->get(FrujaxHeaders::FRUJAX_BLOCK);

        if (!$template instanceof Template || !$frujax instanceof Frujax || null === $block) {
            return;
        }

        if (!\in_array($block, $frujax->getBlocks(), true)) {
            throw new AccessDeniedHttpException(\sprintf('Rendering of block "%s" is not allowed by the @Frujax annotation.', $block));
        }

        $request->attributes->add([
            '_frujax_block' => $block,
            '_frujax_template' => $template->getTemplate(),
        ]);

        $template->setTemplate('@RuworkFrujax/frujax_block.html.twig');
        $template->setVars(\array_merge($template->getVars(), [
            '_frujax_block',
            '_frujax_template',
        ]));
    }
}
