<?php

declare(strict_types=1);

namespace Ruwork\RouteOptionalPrefix;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class LoaderDecorator implements LoaderInterface
{
    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritdoc}
     *
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        $routes = $this->loader->load($resource, $type);

        if (!$routes instanceof RouteCollection) {
            throw new \UnexpectedValueException(sprintf('Decorated route loader is expected to return an instance of %s.', RouteCollection::class));
        }

        foreach ($routes->all() as $name => $route) {
            /** @var Route $route */
            if (null === $variable = $route->getOption('prefix_variable')) {
                continue;
            }

            if (!$route->hasDefault($variable)) {
                throw new \LogicException(sprintf('Route "%s" with optional prefix "/{%s}" must have a default value for "%2$s".', $name, $variable));
            }

            $path = sprintf('/{%s}%s', $variable, ltrim($route->getPath(), '/'));
            $requirement = sprintf('(%s)/|', $route->getRequirement($variable) ?? '[^/]+');

            $route
                ->setPath($path)
                ->setRequirement($variable, $requirement);
        }

        return $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return $this->loader->supports($resource, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
        return $this->loader->getResolver();
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        $this->loader->setResolver($resolver);
    }
}
