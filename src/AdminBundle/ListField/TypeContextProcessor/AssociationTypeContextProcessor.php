<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\ListField\TypeContextProcessor;

use Doctrine\Common\Persistence\ManagerRegistry;
use Ruwork\AdminBundle\Config\Model\Config;

class AssociationTypeContextProcessor implements TypeContextProcessorInterface
{
    private $registry;

    private $config;

    public function __construct(ManagerRegistry $registry, Config $config)
    {
        $this->registry = $registry;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return 'association';
    }

    /**
     * {@inheritdoc}
     */
    public function process(string $class, ?string $propertyPath, array &$context): void
    {
        $entityMetadata = $this->registry
            ->getManagerForClass($class)
            ->getClassMetadata($class);

        $associationClass = $entityMetadata->getAssociationTargetClass($propertyPath);

        $associationMetadata = $this->registry
            ->getManagerForClass($associationClass)
            ->getClassMetadata($associationClass);

        $context['association_class'] = $associationClass;
        $context['association_has_to_string'] = method_exists($associationClass, '__toString');
        $context['association_id_property'] = $associationMetadata->getIdentifierFieldNames()[0];
        $context['association_single'] = $entityMetadata->isSingleValuedAssociation($propertyPath);
        $context['association_entity_name'] = null;

        foreach ($this->config->entities as $entityConfig) {
            if ($associationClass === $entityConfig->class) {
                $context['association_entity_name'] = $entityConfig->name;
            }
        }
    }
}
