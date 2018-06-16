<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Locator;

use Ruwork\UploadBundle\Exception\EmptyPathException;
use Ruwork\UploadBundle\Metadata\UploadAccessor;

final class UploadLocator implements UploadLocatorInterface
{
    private $accessor;
    private $publicDir;

    public function __construct(UploadAccessor $accessor, string $publicDir)
    {
        $this->accessor = $accessor;
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

    /**
     * {@inheritdoc}
     */
    public function locateUpload($object): string
    {
        $path = (string) $this->accessor->getPath($object);

        return $this->locatePath($path);
    }
}
