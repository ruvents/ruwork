<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\DataMapper;

use Ruwork\UploadBundle\Entity\AbstractUpload;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CallbackUploadFactory implements UploadFactoryInterface
{
    private $factory;

    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function createUpload(UploadedFile $uploadedFile, string $path, \Traversable $forms): AbstractUpload
    {
        return call_user_func($this->factory, $uploadedFile, $path, $forms);
    }
}
