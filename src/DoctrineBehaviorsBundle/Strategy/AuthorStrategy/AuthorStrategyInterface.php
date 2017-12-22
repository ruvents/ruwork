<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Strategy\AuthorStrategy;

use Doctrine\ORM\Mapping\ClassMetadata;

interface AuthorStrategyInterface
{
    /**
     * @return null|string|object
     */
    public function getAuthor(ClassMetadata $metadata, string $property);
}
