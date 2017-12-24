<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\ListField\TypeGuesser;

use Doctrine\Common\Persistence\ManagerRegistry;
use Ruwork\UploadBundle\Entity\AbstractUpload;

class UploadTypeGuesser implements TypeGuesserInterface
{
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

        if (!$metadata->hasAssociation($propertyPath) || !$metadata->isSingleValuedAssociation($propertyPath)) {
            return null;
        }

        $targetClass = $metadata->getAssociationTargetClass($propertyPath);

        return is_subclass_of($targetClass, AbstractUpload::class) ? 'upload' : null;
    }
}
