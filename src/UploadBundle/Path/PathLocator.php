<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Path;

use Ruwork\UploadBundle\Exception\EmptyPathException;

final class PathLocator implements PathLocatorInterface
{
    private $publicDir;

    public function __construct(string $publicDir)
    {
        $this->publicDir = $publicDir;
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
