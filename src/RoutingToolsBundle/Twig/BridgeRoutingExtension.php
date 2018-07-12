<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\Twig;

use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Component\PropertyAccess\Exception\ExceptionInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class BridgeRoutingExtension extends RoutingExtension
{
    private $router;
    private $accessor;

    public function __construct(UrlGeneratorInterface $generator, RouterInterface $router, PropertyAccessorInterface $accessor)
    {
        parent::__construct($generator);
        $this->router = $router;
        $this->accessor = $accessor;
    }

    public function getUrl($name, $parameters = [], $relative = false): string
    {
        if (\is_object($parameters)) {
            $parameters = $this->getObjectParameters($name, $parameters);
        }

        return parent::getUrl($name, $parameters, $relative);
    }

    public function getPath($name, $parameters = [], $relative = false): string
    {
        if (\is_object($parameters)) {
            $parameters = $this->getObjectParameters($name, $parameters);
        }

        return parent::getPath($name, $parameters, $relative);
    }

    private function getObjectParameters($name, $object): array
    {
        $parameters = [];

        $route = $this->router
            ->getRouteCollection()
            ->get($name);

        if (null !== $route) {
            foreach ($route->compile()->getPathVariables() as $variable) {
                try {
                    $parameters[$variable] = $this->accessor->getValue($object, $variable);
                } catch (ExceptionInterface $exception) {
                }
            }
        }

        return $parameters;
    }
}
