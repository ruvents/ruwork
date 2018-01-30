<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\PathGenerator;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PathGeneratorTest extends TestCase
{
    public function test(): void
    {
        $generator = new PathGenerator('uploads/');

        $this->assertAttributeSame('uploads', 'uploadsDir', $generator);

        $file = $this->createMock(UploadedFile::class);
        $file->method('guessExtension')->willReturn('txt');

        $path = $generator->generatePath($file);

        $this->assertRegExp('/^uploads\/[0-9a-f]{2}\/[0-9a-f]{30}\.txt$/', $path);
    }
}
