<?php

namespace Ruwork\ManualAuthBundle\Security;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class Provider implements AuthenticationProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        throw new AuthenticationException();
    }

    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token)
    {
        return false;
    }
}
