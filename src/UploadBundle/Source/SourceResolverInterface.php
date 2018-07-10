<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source;

interface SourceResolverInterface
{
    /**
     * @param mixed $source
     */
    public function resolve($source): ResolvedSourceInterface;
}
