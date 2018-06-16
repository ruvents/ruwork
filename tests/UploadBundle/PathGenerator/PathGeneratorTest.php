<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\PathGenerator;

use PHPUnit\Framework\TestCase;

class PathGeneratorTest extends TestCase
{
    public function test(): void
    {
        $generator = new PathGenerator('uploads');

        $this->assertAttributeSame('uploads', 'uploadsDir', $generator);

        $path = $generator->generate([
            PathGeneratorInterface::EXTENSION => 'txt',
        ]);

        $this->assertRegExp('/^uploads\/[0-9a-f]{2}\/[0-9a-f]{30}\.txt$/', $path);
    }
}
