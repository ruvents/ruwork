<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle;

use PHPUnit\Framework\TestCase;
use Ruwork\UploadBundle\Entity\AbstractUpload;

class UploadManagerTest extends TestCase
{
    public function testGetPathname(): void
    {
        $upload = $this->createUpload('path/file.txt');
        $manager = new UploadManager('uploads/');
        $this->assertSame('uploads/path/file.txt', $manager->getPathname($upload));
    }

    private function createUpload(string $path): AbstractUpload
    {
        $upload = $this->getMockForAbstractClass(AbstractUpload::class, [], '', false);

        \Closure::bind(function ($upload, string $path): void {
            $upload->path = $path;
        }, null, AbstractUpload::class)($upload, $path);

        return $upload;
    }
}
