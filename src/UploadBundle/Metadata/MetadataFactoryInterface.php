<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Metadata;

interface MetadataFactoryInterface
{
    public function getMetadata(string $class): Metadata;
}
