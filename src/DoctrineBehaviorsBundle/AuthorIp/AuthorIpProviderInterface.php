<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\AuthorIp;

use Doctrine\ORM\Mapping\ClassMetadata;

interface AuthorIpProviderInterface
{
    public function getAuthorIp(ClassMetadata $metadata, string $property);
}
