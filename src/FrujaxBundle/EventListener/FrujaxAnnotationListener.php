<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\Annotation\Frujax;
use Ruwork\FrujaxBundle\FrujaxUtils;
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

        if (!FrujaxUtils::isFrujaxRequest($request) ||
            !($template = $request->attributes->get('_template')) instanceof Template ||
            !($frujax = $request->attributes->get('_ruwork_frujax')) instanceof Frujax ||
            null === $block = FrujaxUtils::getFrujaxBlock($request)
        ) {
            return;
        }

        $blocks = $frujax->getBlocks();

        if (null !== $blocks && !\in_array($block, $blocks, true)) {
            throw new AccessDeniedHttpException(\sprintf('Rendering of block "%s" is not allowed by the @Frujax annotation.', $block));
        }

        $request->attributes->add([
            '_ruwork_frujax_block' => $block,
            '_ruwork_frujax_template' => $template->getTemplate(),
        ]);

        $template->setTemplate('@RuworkFrujax/frujax_block.html.twig');
    }
}
