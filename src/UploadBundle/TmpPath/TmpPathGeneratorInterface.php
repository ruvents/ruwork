<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\TmpPath;

interface TmpPathGeneratorInterface
{
    public function generateTmpPath(): string;
}
