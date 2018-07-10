<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Path;

use Ruwork\UploadBundle\Exception\EmptyPathException;

final class PathGenerator implements PathGeneratorInterface, PathLocatorInterface
{
    private $uploadsDir;
    private $publicDir;

    public function __construct(string $uploadsDir, string $publicDir)
    {
        $this->uploadsDir = $uploadsDir;
        $this->publicDir = $publicDir;
    }

    /**
     * {@inheritdoc}
     */
    public function generatePath(?string $extension = null): string
    {
        $random = bin2hex(random_bytes(16));

        return $this->uploadsDir.'/'.substr($random, 0, 2).'/'.substr($random, 2).($extension ? '.'.$extension : '');
    }

    /**
     * {@inheritdoc}
     */
    public function locatePath(string $path): string
    {
        if (!$path) {
            throw new EmptyPathException();
        }

        return $this->publicDir.'/'.$path;
    }
}
