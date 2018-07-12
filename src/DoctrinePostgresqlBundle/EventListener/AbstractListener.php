<?php

declare(strict_types=1);

namespace Ruwork\DoctrinePostgresqlBundle\EventListener;

use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class AbstractListener
{
    protected function checkPostgresqlPlatform(AbstractPlatform $platform): void
    {
        if ('postgresql' !== $platform->getName()) {
            throw new \RuntimeException(\sprintf('Listener "%s" can be attached only to PostgreSQL platform connections.', \get_class($this)));
        }
    }
}
