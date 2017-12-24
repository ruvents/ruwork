<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\ListField\TypeGuesser;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\Type;

class DoctrineTypeGuesser implements TypeGuesserInterface
{
    private $map = [
        Type::TARRAY => 'json',
        Type::SIMPLE_ARRAY => 'simple_array',
        Type::JSON_ARRAY => 'json',
        Type::JSON => 'json',
        Type::BIGINT => 'plain',
        Type::BOOLEAN => 'bool',
        Type::DATETIME => 'date_time',
        Type::DATETIME_IMMUTABLE => 'date_time',
        Type::DATETIMETZ => 'date_time',
        Type::DATETIMETZ_IMMUTABLE => 'date_time',
        Type::DATE => 'date',
        Type::DATE_IMMUTABLE => 'date',
        Type::TIME => 'time',
        Type::TIME_IMMUTABLE => 'time',
        Type::DECIMAL => 'plain',
        Type::INTEGER => 'plain',
        Type::OBJECT => 'php_type',
        Type::SMALLINT => 'plain',
        Type::STRING => 'plain',
        Type::TEXT => 'plain',
        Type::BINARY => 'binary',
        Type::BLOB => 'blob',
        Type::FLOAT => 'plain',
        Type::GUID => 'plain',
        Type::DATEINTERVAL => 'date_interval',
    ];

    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function guess(string $class, string $propertyPath): ?string
    {
        if (!$manager = $this->registry->getManagerForClass($class)) {
            return null;
        }

        $metadata = $manager->getClassMetadata($class);

        if ($metadata->hasField($propertyPath)) {
            $type = (string) $metadata->getTypeOfField($propertyPath);

            return $this->map[$type] ?? null;
        }

        if ($metadata->hasAssociation($propertyPath)) {
            return 'association';
        }

        return null;
    }
}
