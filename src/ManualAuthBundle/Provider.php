<?php

declare(strict_types=1);

namespace Ruwork\ManualAuthBundle;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class Provider implements AuthenticationProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token): void
    {
        throw new AuthenticationException();
    }

    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token): bool
    {
        return false;
    }
}
