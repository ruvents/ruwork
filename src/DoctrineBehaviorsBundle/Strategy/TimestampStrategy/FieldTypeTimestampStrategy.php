<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Strategy\TimestampStrategy;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ClassMetadata;

class FieldTypeTimestampStrategy implements TimestampStrategyInterface
{
    protected static $immutableTypes = [
        Type::DATETIME_IMMUTABLE => true,
        Type::DATETIMETZ_IMMUTABLE => true,
        Type::DATE_IMMUTABLE => true,
        Type::TIME_IMMUTABLE => true,
    ];

    /**
     * {@inheritdoc}
     */
    public function getTimestamp(ClassMetadata $metadata, string $property): \DateTimeInterface
    {
        if (!$metadata->hasField($property)) {
            throw new \LogicException(sprintf('Property "%s" of class "%s" is not a mapped field.', $property, $metadata->getName()));
        }

        $type = (string) $metadata->getTypeOfField($property);

        if (isset(self::$immutableTypes[$type])) {
            return new \DateTimeImmutable();
        }

        return new \DateTime();
    }
}
