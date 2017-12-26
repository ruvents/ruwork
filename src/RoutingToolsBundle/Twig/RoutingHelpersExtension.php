<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RoutingHelpersExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_route', [$this, 'getCurrentRoute']),
            new TwigFunction('current_route_params', [$this, 'getCurrentRouteParams']),
        ];
    }

    public function getCurrentRoute(): ?string
    {
        if (null === $request = $this->requestStack->getCurrentRequest()) {
            return null;
        }

        return $request->attributes->get('_route');
    }

    public function getCurrentRouteParams(): ?array
    {
        if (null === $request = $this->requestStack->getCurrentRequest()) {
            return null;
        }

        return $request->attributes->get('_route_params', []);
    }
}
