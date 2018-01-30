<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\PathGenerator;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class PathGenerator implements PathGeneratorInterface
{
    private $uploadsDir;

    public function __construct(string $uploadsDir)
    {
        $this->uploadsDir = trim($uploadsDir, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function generatePath(UploadedFile $uploadedFile): string
    {
        $extension = $uploadedFile->guessExtension();
        $random = bin2hex(random_bytes(16));

        return $this->uploadsDir
            .'/'.substr($random, 0, 2)
            .'/'.substr($random, 2)
            .($extension ? '.'.$extension : '');
    }
}
