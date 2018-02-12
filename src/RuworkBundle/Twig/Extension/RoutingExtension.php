<?php

namespace Ruvents\RuworkBundle\Twig\Extension;

use Symfony\Bridge\Twig\Extension\RoutingExtension as BaseRoutingExtension;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class RoutingExtension extends BaseRoutingExtension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    public function __construct(UrlGeneratorInterface $generator, RouterInterface $router, PropertyAccessorInterface $accessor)
    {
        parent::__construct($generator);
        $this->router = $router;
        $this->accessor = $accessor;
    }

    public function getUrl($name, $parameters = [], $relative = false)
    {
        if (is_object($parameters)) {
            $parameters = $this->getObjectParameters($name, $parameters);
        }

        return parent::getUrl($name, $parameters, $relative);
    }

    public function getPath($name, $parameters = [], $relative = false)
    {
        if (is_object($parameters)) {
            $parameters = $this->getObjectParameters($name, $parameters);
        }

        return parent::getPath($name, $parameters, $relative);
    }

    private function getObjectParameters($name, $object)
    {
        $parameters = [];

        $route = $this->router
            ->getRouteCollection()
            ->get($name);

        if (null !== $route) {
            foreach ($route->compile()->getPathVariables() as $variable) {
                if ($this->accessor->isReadable($object, $variable)) {
                    $parameters[$variable] = $this->accessor->getValue($object, $variable);
                }
            }
        }

        return $parameters;
    }
}
