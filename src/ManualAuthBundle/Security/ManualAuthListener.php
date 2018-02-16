<?php

declare(strict_types=1);

namespace Ruwork\ManualAuthBundle\Security;

use Ruwork\ManualAuthBundle\ManualAuthScheduler;
use Symfony\Bundle\SecurityBundle\Security\FirewallConfig;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;

final class ManualAuthListener implements ListenerInterface
{
    private $manager;
    private $firewallConfig;
    private $scheduler;

    /**
     * @var null|RememberMeServicesInterface
     */
    private $rememberMeServices;

    public function __construct(
        AuthenticationManagerInterface $manager,
        FirewallConfig $firewallConfig,
        ManualAuthScheduler $scheduler
    ) {
        $this->manager = $manager;
        $this->firewallConfig = $firewallConfig;
        $this->scheduler = $scheduler;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(GetResponseEvent $event)
    {
    }

    public function onKernelResponse(FilterResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->hasSession()) {
            return;
        }

        if ($this->firewallConfig->isStateless()) {
            return;
        }

        $token = $this->scheduler->getForFirewall($this->firewallConfig->getName());

        if (null === $token) {
            return;
        }

        $token = $this->manager->authenticate($token);
        $context = $this->firewallConfig->getContext();

        $request->getSession()->set('_security_'.$context, serialize($token));

        if (null !== $this->rememberMeServices) {
            $this->rememberMeServices->loginSuccess($request, $event->getResponse(), $token);
        }
    }

    public function setRememberMeServices(RememberMeServicesInterface $rememberMeServices): void
    {
        $this->rememberMeServices = $rememberMeServices;
    }
}
