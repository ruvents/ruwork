<?php

declare(strict_types=1);

namespace Ruwork\RouteOptionalPrefix;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
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
    public function setContext(RequestContext $context): void
    {
        $this->router->setContext($context);
    }

    /**
     * {@inheritdoc}
     */
    public function getContext(): RequestContext
    {
        return $this->router->getContext();
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo): array
    {
        $parameters = $this->router->match($pathinfo);
        $this->postMatch($parameters);

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        $this->preGenerate($name, $parameters);

        return $this->router->generate($name, $parameters, $referenceType);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(): RouteCollection
    {
        return $this->router->getRouteCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function matchRequest(Request $request): array
    {
        $parameters = $this->router instanceof RequestMatcherInterface
            ? $this->router->matchRequest($request)
            : $this->router->match($request->getPathInfo());

        $this->postMatch($parameters);

        return $parameters;
    }

    private function postMatch(array &$parameters): void
    {
        $route = $this->getRouteCollection()->get($parameters['_route']);

        if (null === $variable = $route->getOption('prefix_variable')) {
            return;
        }

        $parameters[$variable] = '' === $parameters[$variable]
            ? $route->getOption('prefix_default')
            : rtrim($parameters[$variable], '/');
    }

    private function preGenerate(string $name, array &$parameters): void
    {
        if (null === $route = $this->getRouteCollection()->get($name)) {
            return;
        }

        if (null === $variable = $route->getOption('prefix_variable')) {
            return;
        }

        if (!isset($parameters[$variable])) {
            return;
        }

        if ($route->getOption('prefix_default') === $parameters[$variable]) {
            unset($parameters[$variable]);

            return;
        }

        $parameters[$variable] .= '/';
    }
}
