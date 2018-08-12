<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Metadata;

use Doctrine\Common\Persistence\Proxy;

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
        if (false !== $pos = \strrpos($class, '\\'.Proxy::MARKER.'\\')) {
            $class = \substr($class, $pos + Proxy::MARKER_LENGTH + 2);
        }

        return $this->factory->getMetadata($class);
    }
}
