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
     */
    public function load($resource, $type = null)
    {
        $routes = $this->loader->load($resource, $type);

        if (!$routes instanceof RouteCollection) {
            return $routes;
        }

        foreach ($routes->all() as $route) {
            /** @var Route $route */
            if (null === $variable = $route->getOption('prefix_variable')) {
                continue;
            }

            $route
                ->setPath(sprintf('/{%s}%s', $variable, ltrim($route->getPath(), '/')))
                ->addDefaults([
                    $variable => '',
                ])
                ->addRequirements([
                    $variable => sprintf('((%s)/|)', $route->getOption('prefix_requirements') ?? '[^/]+'),
                ]);
        }

        return $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null): bool
    {
        return $this->loader->supports($resource, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver(): LoaderResolverInterface
    {
        return $this->loader->getResolver();
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver): void
    {
        $this->loader->setResolver($resolver);
    }
}
