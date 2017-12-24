<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\MappedSuperclass()
 */
abstract class AbstractUpload
{
    /**
     * @ORM\Column(name="id", type="string")
     * @ORM\Id()
     */
    protected $path;

    private $uploadedFile;

    public function __construct(UploadedFile $uploadedFile, string $path)
    {
        $this->uploadedFile = $uploadedFile;
        $this->path = $path;
    }

    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
