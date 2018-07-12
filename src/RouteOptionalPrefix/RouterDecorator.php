<?php

declare(strict_types=1);

namespace Ruwork\RouteOptionalPrefix;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

class RouterDecorator implements RouterInterface, RequestMatcherInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->router->getContext();
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->router->setContext($context);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection()
    {
        return $this->router->getRouteCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        $parameters = $this->router->match($pathinfo);
        $this->postMatch($parameters);

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function matchRequest(Request $request)
    {
        $parameters = $this->router instanceof RequestMatcherInterface
            ? $this->router->matchRequest($request)
            : $this->router->match($request->getPathInfo());

        $this->postMatch($parameters);

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        $this->preGenerate($name, $parameters);

        return $this->router->generate($name, $parameters, $referenceType);
    }

    private function postMatch(array &$parameters): void
    {
        if (!isset($parameters['_route'])) {
            return;
        }

        $route = $this->getRouteCollection()->get($parameters['_route']);

        if (null === $variable = $route->getOption('prefix_variable')) {
            return;
        }

        $value = $parameters[$variable];

        if ('' === $value) {
            $value = $route->getDefault($variable);
        } else {
            // remove / at the end
            $value = \substr($value, 0, -1);
        }

        $parameters[$variable] = $value;
    }

    private function preGenerate(string $name, array &$parameters): void
    {
        if (null === $route = $this->getRouteCollection()->get($name)) {
            return;
        }

        if (null === $variable = $route->getOption('prefix_variable')) {
            return;
        }

        $default = $route->getDefault($variable);

        $value = $parameters[$variable]
            ?? $this->router->getContext()->getParameter($variable)
            ?? $default;

        if ($default === $value) {
            $value = '';
        } else {
            $value .= '/';
        }

        $parameters[$variable] = $value;
    }
}
