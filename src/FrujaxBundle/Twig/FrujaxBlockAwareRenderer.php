<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\Twig;

use Ruwork\FrujaxBundle\FrujaxUtils;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

final class FrujaxBlockAwareRenderer
{
    private $twig;
    private $requestStack;

    public function __construct(Environment $twig, RequestStack $requestStack)
    {
        $this->twig = $twig;
        $this->requestStack = $requestStack;
    }

    public function render(string $template, array $context = [], callable $blockValidator = null): string
    {
        if (null !== $block = $this->getBlock($blockValidator)) {
            return $this->twig->load($template)->renderBlock($block, $context);
        }

        return $this->twig->render($template, $context);
    }

    public function display(string $template, array $context = [], callable $blockValidator = null): void
    {
        if (null !== $block = $this->getBlock($blockValidator)) {
            $this->twig->load($template)->displayBlock($block, $context);

            return;
        }

        $this->twig->display($template, $context);
    }

    private function getBlock(?callable $validator): ?string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null !== $request &&
            FrujaxUtils::isFrujaxRequest($request) &&
            null !== ($block = FrujaxUtils::getFrujaxBlock($request)) &&
            (null === $validator || true === $validator($block))
        ) {
            return $block;
        }

        return null;
    }
}
