<?php

declare(strict_types=1);

namespace Ruwork\ManualAuthBundle;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ManualAuthenticator
{
    private $tokens = [];

    public function schedule(string $firewall, TokenInterface $token): void
    {
        $this->tokens[$firewall] = $token;
    }

    public function getForFirewall(string $firewall): ?TokenInterface
    {
        return $this->tokens[$firewall] ?? null;
    }
}
