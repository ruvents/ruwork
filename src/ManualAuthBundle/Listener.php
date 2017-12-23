<?php

declare(strict_types=1);

namespace Ruwork\ManualAuthBundle;

use Symfony\Bundle\SecurityBundle\Security\FirewallConfig;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;

class Listener implements ListenerInterface
{
    private $authenticator;
    private $firewallConfig;
    private $manager;

    /**
     * @var null|RememberMeServicesInterface
     */
    private $rememberMeServices;

    public function __construct(ManualAuthenticator $authenticator, FirewallConfig $firewallConfig, AuthenticationManagerInterface $manager)
    {
        $this->authenticator = $authenticator;
        $this->firewallConfig = $firewallConfig;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(GetResponseEvent $event): void
    {
    }

    public function onKernelResponse(FilterResponseEvent $event): void
    {
        if (!$event->isMasterRequest()
            || !($request = $event->getRequest())->hasSession()
            || $this->firewallConfig->isStateless()
            || null === $token = $this->authenticator->getForFirewall($this->firewallConfig->getName())
        ) {
            return;
        }

        try {
            $token = $this->manager->authenticate($token);
        } catch (AuthenticationException $exception) {
            return;
        }

        $request->getSession()->set('_security_'.$this->firewallConfig->getContext(), serialize($token));

        if (null !== $this->rememberMeServices) {
            $this->rememberMeServices->loginSuccess($request, $event->getResponse(), $token);
        }
    }

    public function setRememberMeServices(RememberMeServicesInterface $rememberMeServices): void
    {
        $this->rememberMeServices = $rememberMeServices;
    }
}
