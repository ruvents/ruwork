<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Author;

use Doctrine\ORM\Mapping\ClassMetadata;

interface AuthorProviderInterface
{
    /**
     * @return null|object|string
     */
    public function getAuthor(ClassMetadata $metadata, string $property);
}
