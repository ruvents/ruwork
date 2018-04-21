<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\EventListener;

use Ruwork\FrujaxBundle\Annotation\FrujaxBlocks;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxHeaders;
use Ruwork\FrujaxBundle\HttpFoundation\FrujaxRequestChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

final class FrujaxTemplateListener implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

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

        $block = $request->headers->get(FrujaxHeaders::FRUJAX_BLOCK);

        if (null === $block) {
            return;
        }

        $template = $request->attributes->get('_template');

        if (!$template instanceof Template) {
            return;
        }

        if (!$tpl = $template->getTemplate()) {
            throw new \UnexpectedValueException('Template is not set.');
        }

        $frujaxBlocks = $request->attributes->get('_frujax_blocks');

        if (!$frujaxBlocks instanceof FrujaxBlocks) {
            throw new AccessDeniedHttpException(\sprintf('Rendering of block "%s" is not allowed. Add @FrujaxBlocks annotation to the action.', $block));
        }

        if (!\in_array($block, $frujaxBlocks->getBlocks(), true)) {
            throw new AccessDeniedHttpException(\sprintf('Rendering of block "%s" is not allowed by the @FrujaxBlocks annotation.', $block));
        }

        if (!$this->twig->loadTemplate($tpl)->hasBlock($block, [])) {
            throw new NotFoundHttpException(\sprintf('Block "%s" does not exist in "%s".', $block, $tpl));
        }

        $request->attributes->add([
            '_frujax_block' => $block,
            '_frujax_template' => $tpl,
        ]);

        $template->setTemplate('@RuworkFrujax/frujax_block.html.twig');
        $template->setVars(\array_merge($template->getVars(), [
            '_frujax_block',
            '_frujax_template',
        ]));
    }
}
