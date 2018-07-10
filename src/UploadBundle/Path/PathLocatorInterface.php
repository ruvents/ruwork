<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Path;

interface PathLocatorInterface
{
    public function locatePath(string $path): string;
}
