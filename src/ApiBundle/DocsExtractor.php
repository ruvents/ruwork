<?php

namespace Ruwork\ApiBundle;

use Doctrine\Common\Annotations\Reader;
use Ruwork\ApiBundle\Annotations\Doc;
use Symfony\Component\Routing\RouterInterface;

class DocsExtractor
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Reader
     */
    private $annotationsReader;

    public function __construct(RouterInterface $router, Reader $annotationsReader)
    {
        $this->router = $router;
        $this->annotationsReader = $annotationsReader;
    }

    /**
     * @return Doc[]
     */
    public function getDocs(): array
    {
        $docs = [];

        foreach ($this->router->getRouteCollection() as $name => $route) {
            if (!Helper::isApiRoute($route)) {
                continue;
            }

            if (null === $classMethod = $this->getControllerClassMethod($route->getDefault('_controller'))) {
                continue;
            }

            if (null === $doc = $this->getDocAnnotation($classMethod)) {
                continue;
            }

            $doc->endpoint = $route->getPath();
            $doc->methods = $route->getMethods();
            $doc->block = $doc->block ?? $name;

            $docs[] = $doc;
        }

        $this->sortDocs($docs);

        return $docs;
    }

    /**
     * @param mixed $_controller
     *
     * @return null|string
     */
    private function getControllerClassMethod($_controller)
    {
        if (is_string($_controller) && false !== strpos($_controller, '::')) {
            return $_controller;
        }

        if (is_array($_controller) && is_callable($_controller)) {
            $_controller = array_values($_controller);

            return get_class($_controller[0]).'::'.$_controller[1];
        }

        if (is_object($_controller) && method_exists($_controller, '__invoke')) {
            return get_class($_controller).'::__invoke';
        }

        return null;
    }

    /**
     * @param string $classMethod
     *
     * @return null|object|Doc
     */
    private function getDocAnnotation(string $classMethod)
    {
        return $this->annotationsReader
            ->getMethodAnnotation(new \ReflectionMethod($classMethod), Doc::class);
    }

    /**
     * @param Doc[] $docs
     */
    private function sortDocs(array & $docs)
    {
        usort($docs, function (Doc $a, Doc $b) {
            return $a->priority === $b->priority
                ? ($a->endpoint === $b->endpoint
                    ? 0
                    : $a->endpoint > $b->endpoint ? 1 : -1
                )
                : ($a->priority > $b->priority ? 1 : -1);
        });
    }
}
