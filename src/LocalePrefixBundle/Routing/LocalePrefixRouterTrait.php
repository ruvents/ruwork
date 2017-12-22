<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle\Routing;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouteCollection;

trait LocalePrefixRouterTrait
{
    /**
     * @return RouteCollection
     */
    abstract protected function getRouteCollection();

    abstract protected function getRequestStack(): ?RequestStack;

    abstract protected function getDefaultLocale(): string;

    final protected function preGenerate(string $name, array &$parameters): void
    {
        if (null === $route = $this->getRouteCollection()->get($name)) {
            return;
        }

        if (true !== $route->getOption('locale_prefixed')) {
            return;
        }

        $locale = $this->getLocale($parameters);
        $parameters['_locale'] = $this->getDefaultLocale() === $locale ? '' : $locale.'/';
    }

    final protected function postMatch(array &$parameters): void
    {
        if (!isset($parameters['_route']) || !isset($parameters['_locale'])) {
            return;
        }

        if (null === $route = $this->getRouteCollection()->get($parameters['_route'])) {
            return;
        }

        if (true !== $route->getOption('locale_prefixed')) {
            return;
        }

        $parameters['_locale'] = isset($parameters['_locale']) && '' !== $parameters['_locale']
            ? rtrim($parameters['_locale'], '/')
            : $this->getDefaultLocale();
    }

    private function getLocale(array $parameters): string
    {
        if (isset($parameters['_locale'])) {
            return $parameters['_locale'];
        }

        if (null === $this->getRequestStack()) {
            return $this->getDefaultLocale();
        }

        if (null === $request = $this->getRequestStack()->getCurrentRequest()) {
            return $this->getDefaultLocale();
        }

        return $request->getLocale();
    }
}
