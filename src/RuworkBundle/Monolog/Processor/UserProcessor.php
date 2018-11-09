<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Monolog\Processor;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UserProcessor
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(array $record): array
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            return $record;
        }

        $record['context']['user']['username'] = $token->getUsername();

        return $record;
    }
}
