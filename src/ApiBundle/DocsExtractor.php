<?php

declare(strict_types=1);

namespace Ruwork\ApiBundle;

use Doctrine\Common\Annotations\Reader;
use Ruwork\ApiBundle\Annotations\Doc;
use Symfony\Component\Routing\RouterInterface;

final class DocsExtractor
{
    private $router;
    private $annotationsReader;

    public function __construct(RouterInterface $router, Reader $reader)
    {
        $this->router = $router;
        $this->annotationsReader = $reader;
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

            $_controller = $route->getDefault('_controller');

            if (!\is_string($_controller)) {
                continue;
            }

            if (null === $classMethod = $this->getControllerClassMethod($_controller)) {
                continue;
            }

            $doc = $this->annotationsReader->getMethodAnnotation(
                new \ReflectionMethod($classMethod),
                Doc::class
            );

            if (!$doc instanceof Doc) {
                continue;
            }

            $doc->endpoint = $route->getPath();
            $doc->methods = $route->getMethods();
            $doc->block = $doc->block ?? $name;

            $docs[] = $doc;
        }

        \usort($docs, static function (Doc $a, Doc $b): int {
            return $a->priority <=> $b->priority ?: $a->endpoint <=> $b->endpoint;
        });

        return $docs;
    }

    private function getControllerClassMethod(string $_controller): ?string
    {
        if (false !== \strpos($_controller, '::')) {
            return $_controller;
        }

        if (\class_exists($_controller) && \method_exists($_controller, '__invoke')) {
            return $_controller.'::__invoke';
        }

        return null;
    }
}
