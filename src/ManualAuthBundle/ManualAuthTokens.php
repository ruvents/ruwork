<?php

declare(strict_types=1);

namespace Ruwork\ManualAuthBundle;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class ManualAuthTokens
{
    private $tokens = [];

    public function pop(string $firewall): ?TokenInterface
    {
        $token = $this->tokens[$firewall] ?? null;
        unset($this->tokens[$firewall]);

        return $token;
    }

    public function set(string $firewall, TokenInterface $token): void
    {
        $this->tokens[$firewall] = $token;
    }
}
