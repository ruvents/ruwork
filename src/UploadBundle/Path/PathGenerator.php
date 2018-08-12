<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Path;

final class PathGenerator implements PathGeneratorInterface
{
    private $uploadsDir;

    public function __construct(string $uploadsDir)
    {
        $this->uploadsDir = $uploadsDir;
    }

    /**
     * {@inheritdoc}
     */
    public function generatePath(?string $extension = null): string
    {
        $random = \bin2hex(\random_bytes(16));

        return $this->uploadsDir.'/'.\substr($random, 0, 2).'/'.\substr($random, 2).($extension ? '.'.$extension : '');
    }
}
