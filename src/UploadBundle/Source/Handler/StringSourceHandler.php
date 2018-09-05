<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source\Handler;

use Symfony\Component\Filesystem\Filesystem;

final class StringSourceHandler implements SourceHandlerInterface
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($source): bool
    {
        return \is_string($source);
    }

    /**
     * {@inheritdoc}
     */
    public function write($source, string $target): void
    {
        $this->filesystem->copy($source, $target, true);
    }
}
