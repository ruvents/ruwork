<?php

namespace Ruwork\ManualAuthBundle\Security;

use Symfony\Bundle\SecurityBundle\Security\FirewallConfig;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;

class Listener implements ListenerInterface, EventSubscriberInterface
{
    /**
     * @var AuthList
     */
    private $list;

    /**
     * @var FirewallConfig
     */
    private $firewallConfig;

    /**
     * @var AuthenticationManagerInterface
     */
    private $manager;

    /**
     * @var RememberMeServicesInterface|null
     */
    private $rememberMeServices;

    public function __construct(
        AuthList $list,
        FirewallConfig $firewallConfig,
        AuthenticationManagerInterface $manager
    ) {
        $this->list = $list;
        $this->firewallConfig = $firewallConfig;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', -4000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function handle(GetResponseEvent $event)
    {
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->hasSession()) {
            return;
        }

        $firewall = $this->firewallConfig->getName();
        $sessionKey = '_security_'.$this->firewallConfig->getContext();

        if ($this->firewallConfig->isStateless()) {
            $request->getSession()->remove($sessionKey);

            return;
        }

        if (!$this->list->hasToken($firewall)) {
            return;
        }

        $token = $this->list->pullToken($firewall);

        try {
            $token = $this->manager->authenticate($token);
        } catch (AuthenticationException $exception) {
            return;
        }

        $request->getSession()->set($sessionKey, serialize($token));

        if (null !== $this->rememberMeServices) {
            $this->rememberMeServices->loginSuccess($request, $event->getResponse(), $token);
        }
    }

    public function setRememberMeServices(RememberMeServicesInterface $rememberMeServices)
    {
        $this->rememberMeServices = $rememberMeServices;
    }
}
