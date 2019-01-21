<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Menu;

use Ruwork\AdminBundle\Config\Model\Menu\AbstractItemConfig;
use Ruwork\AdminBundle\Config\Model\Menu\ChildrenItemConfig;
use Ruwork\AdminBundle\Config\Model\Menu\EntityItemConfig;
use Ruwork\AdminBundle\Config\Model\Menu\RouteItemConfig;
use Ruwork\AdminBundle\Config\Model\Menu\UrlItemConfig;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MenuResolver
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ExpressionLanguage
     */
    private $language;

    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $urlGenerator, ExpressionLanguage $language)
    {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
        $this->language = $language;
    }

    /**
     * @param AbstractItemConfig[] $items
     *
     * @return ResolvedMenuItem[]
     */
    public function resolve(array $items): array
    {
        return array_map([$this, 'resolveItem'], $items);
    }

    private function resolveItem(AbstractItemConfig $item)
    {
        switch (true) {
            case $item instanceof UrlItemConfig:
                return $this->resolveUrlItem($item);

            case $item instanceof ChildrenItemConfig:
                return $this->resolveChildrenItem($item);

            case $item instanceof RouteItemConfig:
                return $this->resolveRouteItem($item);

            case $item instanceof EntityItemConfig:
                return $this->resolveEntityItem($item);
        }

        throw new \InvalidArgumentException(sprintf('Item of class "%s" is not supported.', \get_class($item)));
    }

    private function resolveUrlItem(UrlItemConfig $item): ResolvedMenuItem
    {
        return new ResolvedMenuItem($item->title, $item->attributes, $item->url);
    }

    private function resolveChildrenItem(ChildrenItemConfig $item): ResolvedMenuItem
    {
        $active = false;
        $children = [];

        foreach ($item->children as $child) {
            $children[] = $resolvedChild = $this->resolveItem($child);
            $active = $active || $resolvedChild->isActive();
        }

        $active = $active || $this->isActive(null, $item->activeExpression, $item);

        return new ResolvedMenuItem($item->title, $item->attributes, null, $active, $children);
    }

    private function resolveRouteItem(RouteItemConfig $item): ResolvedMenuItem
    {
        return new ResolvedMenuItem(
            $item->title,
            $item->attributes,
            $href = $this->urlGenerator->generate($item->route, $item->routeParams),
            $this->isActive($href, $item->activeExpression, $item)
        );
    }

    private function resolveEntityItem(EntityItemConfig $item): ResolvedMenuItem
    {
        $route = 'ruwork_admin_'.$item->action;
        $routeParams = $item->routeParams + ['ruwork_admin_entity' => $item->entity];

        return new ResolvedMenuItem(
            $item->title,
            $item->attributes,
            $href = $this->urlGenerator->generate($route, $routeParams),
            $this->isActive($href, $item->activeExpression, $item)
        );
    }

    private function isActive(?string $href, ?string $expression, AbstractItemConfig $item): bool
    {
        if ($href) {
            $hrefPath = parse_url($href, PHP_URL_PATH);
            $requestPath = $this->requestStack->getCurrentRequest()->getPathInfo();

            if ($hrefPath === $requestPath) {
                return true;
            }
        }

        if ($expression) {
            return (bool) $this->language->evaluate($expression, [
                'request' => $request = $this->requestStack->getCurrentRequest(),
                'route' => $request->attributes->get('_route'),
                'route_params' => $params = $request->attributes->get('_route_params', []),
                'entity' => $params['ruwork_admin_entity'] ?? null,
                'item' => $item,
                'href' => $href,
            ]);
        }

        return false;
    }
}
