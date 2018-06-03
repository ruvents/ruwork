<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\File\File;

final class TmpFile extends File
{
    public function __construct(string $contents = null)
    {
        $pathname = rtrim(sys_get_temp_dir(), '/\\').\DIRECTORY_SEPARATOR.uniqid();

        file_put_contents($pathname, $contents);

        parent::__construct($pathname);
    }

    public function __destruct()
    {
        $this->unlink();
    }

    public static function createFromResource($handle): self
    {
        return new self(stream_get_contents($handle, -1, 0));
    }

    public function unlink(): void
    {
        @unlink($this->getPathname());
    }
}
