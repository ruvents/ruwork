<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle;

use Doctrine\Common\Persistence\ManagerRegistry;
use Ruwork\BundleTest\AbstractBundleTestCase;
use Ruwork\UploadBundle\EventListener\UploadDoctrineListener;
use Ruwork\UploadBundle\PathGenerator\PathGenerator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class UploadBundleTest extends AbstractBundleTestCase
{
    public function testServices(): void
    {
        $this->loadBundleExtension([
            'uploads_dir' => $uploadsDir = 'path/uploads',
            'public_dir' => $publicDir = 'web',
        ]);
        $this->exposeService('ruwork_upload.path_generator');
        $this->exposeService(UploadManager::class);
        $this->exposeService(UploadDoctrineListener::class);
        $this->compile();

        $this->assertContainerBuilderHasParameter('ruwork_upload.uploads_dir', $uploadsDir);
        $this->assertContainerBuilderHasParameter('ruwork_upload.public_dir', $publicDir);

        $pathGenerator = $this->container->get('ruwork_upload.path_generator');
        $this->assertInstanceOf(PathGenerator::class, $pathGenerator);
        $this->assertAttributeSame($uploadsDir, 'uploadsDir', $pathGenerator);

        $manager = $this->container->get(UploadManager::class);
        $this->assertInstanceOf(UploadManager::class, $manager);
        $this->assertAttributeSame($publicDir, 'publicDir', $manager);

        $listener = $this->container->get(UploadDoctrineListener::class);
        $this->assertAttributeSame($pathGenerator, 'pathGenerator', $listener);
        $this->assertAttributeSame($manager, 'manager', $listener);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(UploadDoctrineListener::class, 'doctrine.event_subscriber');
    }

    protected function setUpContainer(ContainerBuilder $container): void
    {
        $container->setParameter('kernel.project_dir', __DIR__);
        $container->register('doctrine', ManagerRegistry::class);
    }

    protected function getBundle(): BundleInterface
    {
        return new RuworkUploadBundle();
    }
}
