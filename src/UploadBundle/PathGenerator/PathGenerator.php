<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\PathGenerator;

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
    public function generate(array $attributes): string
    {
        $extension = $attributes[self::EXTENSION] ?? null;
        $random = bin2hex(random_bytes(16));

        return $this->uploadsDir.'/'.substr($random, 0, 2).'/'.substr($random, 2).($extension ? '.'.$extension : '');
    }
}
