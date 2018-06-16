<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\PathGenerator;

interface PathGeneratorInterface
{
    public const EXTENSION = 'path_generator.extension';

    public function generate(array $attributes): string;
}
