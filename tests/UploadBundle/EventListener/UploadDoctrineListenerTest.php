<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;
use Ruwork\UploadBundle\Entity\AbstractUpload;
use Ruwork\UploadBundle\PathGenerator\PathGeneratorInterface;
use Ruwork\UploadBundle\UploadManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadDoctrineListenerTest extends TestCase
{
    public function test(): void
    {
        $path = 'generated/path.txt';
        $publicDir = 'public';

        $file = $this->createMock(UploadedFile::class);
        $file->expects($this->once())
            ->method('move')
            ->with($publicDir.'/'.dirname($path), basename($path));

        $upload = $this->getMockForAbstractClass(AbstractUpload::class, [$file]);

        $pathGenerator = $this->createPathGenerator($path);
        $uploadManager = new UploadManager($publicDir);
        $entityManager = $this->createEntityManager([$upload]);
        $listener = new UploadDoctrineListener($pathGenerator, $uploadManager);

        $this->assertArraySubset(
            [Events::prePersist, Events::onFlush, Events::postFlush],
            $listener->getSubscribedEvents()
        );

        $listener->prePersist(new LifecycleEventArgs($upload, $entityManager));
        $this->assertAttributeSame($path, 'path', $upload);

        $listener->onFlush(new OnFlushEventArgs($entityManager));

        $listener->postFlush(new PostFlushEventArgs($entityManager));
        $this->assertAttributeSame(null, 'uploadedFile', $upload);
    }

    private function createPathGenerator(string $path): PathGeneratorInterface
    {
        $pathGenerator = $this->createMock(PathGeneratorInterface::class);
        $pathGenerator
            ->method('generatePath')
            ->willReturn($path);

        return $pathGenerator;
    }

    private function createEntityManager(array $entities): EntityManagerInterface
    {
        $unitOfWork = $this->createMock(UnitOfWork::class);
        $unitOfWork
            ->method('getScheduledEntityInsertions')
            ->willReturn($entities);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getUnitOfWork')
            ->willReturn($unitOfWork);

        return $entityManager;
    }
}
