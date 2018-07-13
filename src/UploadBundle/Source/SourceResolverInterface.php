<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source;

interface SourceResolverInterface
{
    public function resolve($source): ResolvedSourceInterface;
}
