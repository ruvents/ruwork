<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractUpload
{
    /**
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     */
    protected $path;

    private $uploadedFile;

    public function __construct(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }

    final public function getUploadedFile(): ?UploadedFile
    {
        if (null === $this->uploadedFile) {
            throw new \LogicException('Uploaded file is not available in a persisted upload.');
        }

        return $this->uploadedFile;
    }

    final public function getPath(): string
    {
        if (null === $this->path) {
            throw new \LogicException('Path is not available in a not persisted upload.');
        }

        return $this->path;
    }
}
