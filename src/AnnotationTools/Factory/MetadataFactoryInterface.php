<?php

declare(strict_types=1);

namespace Ruwork\AnnotationTools\Factory;

use Ruwork\AnnotationTools\Metadata\ClassMetadata;

interface MetadataFactoryInterface
{
    public function getMetadata(string $class): ClassMetadata;
}
