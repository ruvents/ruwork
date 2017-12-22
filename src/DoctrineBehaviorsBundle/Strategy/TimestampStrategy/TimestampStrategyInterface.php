<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Strategy\TimestampStrategy;

use Doctrine\ORM\Mapping\ClassMetadata;

interface TimestampStrategyInterface
{
    public function getTimestamp(ClassMetadata $metadata, string $property): \DateTimeInterface;
}
