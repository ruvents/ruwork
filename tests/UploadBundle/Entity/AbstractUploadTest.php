<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Entity;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AbstractUploadTest extends TestCase
{
    public function testUploadedFileException(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Uploaded file is not available in a persisted upload.');

        $upload = $this->getMockForAbstractClass(AbstractUpload::class, [], '', false);
        $upload->getUploadedFile();
    }

    public function testPathException(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Path is not available in a not persisted upload.');

        $file = $this->createMock(UploadedFile::class);
        $upload = $this->getMockForAbstractClass(AbstractUpload::class, [$file]);
        $upload->getPath();
    }
}
