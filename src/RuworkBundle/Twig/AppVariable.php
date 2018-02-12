<?php

namespace Ruvents\RuworkBundle\Twig;

use Symfony\Bridge\Twig\AppVariable as BaseAppVariable;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;

class AppVariable extends BaseAppVariable
{
    /**
     * @var FirewallMap
     */
    private $firewallMap;

    public function setFirewallMap(FirewallMap $firewallMap = null)
    {
        $this->firewallMap = $firewallMap;
    }

    /**
     * @return null|string
     */
    public function getRoute()
    {
        if (null === $request = $this->getRequest()) {
            return null;
        }

        return $request->attributes->get('_route');
    }

    public function getRouteParams(): array
    {
        if (null === $request = $this->getRequest()) {
            return [];
        }

        return $request->attributes->get('_route_params', []);
    }

    /**
     * @return null|string
     *
     * @throws \RuntimeException
     */
    public function getFirewall()
    {
        if (null === $this->firewallMap) {
            throw new \RuntimeException('The "app.firewall" variable is not available.');
        }

        if (null === $request = $this->getRequest()) {
            return null;
        }

        return $this->firewallMap->getFirewallConfig($request)->getName();
    }
}
