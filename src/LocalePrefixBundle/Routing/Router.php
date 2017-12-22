<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Router as BaseRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Router extends BaseRouter
{
    use LocalePrefixRouterTrait;

    /**
     * @var string
     */
    private $defaultLocale = 'en';

    /**
     * @var null|RequestStack
     */
    private $requestStack;

    public function setDefaultLocale(string $defaultLocale): void
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        $this->preGenerate($name, $parameters);

        return parent::generate($name, $parameters, $referenceType);
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        $parameters = parent::match($pathinfo);
        $this->postMatch($parameters);

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function matchRequest(Request $request)
    {
        $parameters = parent::matchRequest($request);
        $this->postMatch($parameters);

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequestStack(): ?RequestStack
    {
        return $this->requestStack;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }
}
