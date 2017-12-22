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

    /**
     * @ORM\Column(type="boolean")
     */
    protected $temporary = true;

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

    public function isTemporary(): bool
    {
        return $this->temporary;
    }

    public function setTemporary(bool $temporary)
    {
        $this->temporary = $temporary;

        return $this;
    }
}
