<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class LoaderDecorator implements LoaderInterface
{
    private $loader;
    private $locales;
    private $defaultLocale;

    public function __construct(LoaderInterface $loader, array $locales, string $defaultLocale)
    {
        $this->loader = $loader;
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
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

            if (true === $route->getOption('locale_prefixed')) {
                $route
                    ->setPath('/{_locale}'.ltrim($route->getPath(), '/'))
                    ->addDefaults(['_locale' => ''])
                    ->addRequirements([
                        '_locale' => implode('|', array_map(function ($locale) {
                            return $locale === $this->defaultLocale ? '' : $locale.'/';
                        }, $this->locales)),
                    ]);
            }
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
    public function setResolver(LoaderResolverInterface $resolver): void
    {
        $this->loader->setResolver($resolver);
    }
}
