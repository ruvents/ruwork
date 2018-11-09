<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Monolog\Processor;

use Symfony\Component\Security\Core\Security;

final class UserProcessor
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(array $record): array
    {
        $user = $this->security->getUser();

        if (null !== $user) {
            $record['context']['user']['username'] = $user->getUsername();
        }

        return $record;
    }
}
