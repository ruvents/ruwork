<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\PathGenerator;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PathGeneratorInterface
{
    public function generatePath(UploadedFile $uploadedFile): string;
}
