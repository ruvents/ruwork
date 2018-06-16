<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Metadata;

use Doctrine\Common\Util\ClassUtils;

final class UnproxyMetadataFactory implements MetadataFactoryInterface
{
    private $factory;

    public function __construct(MetadataFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(string $class): Metadata
    {
        return $this->factory->getMetadata(ClassUtils::getRealClass($class));
    }
}
