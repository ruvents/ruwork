<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Path;

interface PathGeneratorInterface
{
    public function generatePath(?string $extension = null): string;
}
