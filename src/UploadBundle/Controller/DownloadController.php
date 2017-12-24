<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Controller;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Ruwork\UploadBundle\Download\DownloadInterface;
use Ruwork\UploadBundle\Entity\AbstractUpload;
use Ruwork\UploadBundle\UploadManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DownloadController
{
    private $doctrine;

    private $manager;

    public function __construct(ManagerRegistry $doctrine, UploadManager $manager)
    {
        $this->doctrine = $doctrine;
        $this->manager = $manager;
    }

    public function __invoke(string $class, string $path)
    {
        $manager = $this->doctrine->getManagerForClass($class);

        if (null === $manager || !$manager instanceof EntityManagerInterface) {
            throw new \RuntimeException(sprintf('Class "%s" is not a valid entity.', $class));
        }

        /** @var null|AbstractUpload $upload */
        $upload = $manager->find($class, $path);

        if (null === $upload) {
            throw new NotFoundHttpException(sprintf('File "%s" was not found.', $path));
        }

        if ($upload instanceof DownloadInterface) {
            $name = $upload->getDownloadName();
        } else {
            $name = basename($upload->getPath());
        }

        return (new BinaryFileResponse($this->manager->getPathname($upload)))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $name);
    }
}
