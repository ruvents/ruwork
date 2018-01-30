<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\DataMapper;

use Ruwork\UploadBundle\Entity\AbstractUpload;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadDataMapper implements DataMapperInterface
{
    private $factory;
    private $mapper;

    public function __construct(UploadFactoryInterface $factory, DataMapperInterface $mapper = null)
    {
        $this->factory = $factory;
        $this->mapper = $mapper ?? new PropertyPathMapper();
    }

    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms): void
    {
        if (null !== $data && !$data instanceof AbstractUpload) {
            throw new UnexpectedTypeException($data, AbstractUpload::class);
        }

        $this->mapper->mapDataToForms($data, $forms);
    }

    /**
     * {@inheritdoc}
     *
     * @param FormInterface[]|\Traversable $forms
     */
    public function mapFormsToData($forms, &$data): void
    {
        if (null !== $data && !$data instanceof AbstractUpload) {
            throw new UnexpectedTypeException($data, AbstractUpload::class);
        }

        foreach ($forms as $form) {
            if ('uploadedFile' === $form->getName()) {
                if (!$form->isEmpty()) {
                    $data = $this->createUpload($form, $forms);
                }

                break;
            }
        }

        $this->mapper->mapFormsToData($forms, $data);
    }

    private function createUpload(FormInterface $form, \Traversable $forms): AbstractUpload
    {
        $uploadedFile = $form->getData();

        if (!$uploadedFile instanceof UploadedFile) {
            throw new \UnexpectedValueException(sprintf(
                'Uploaded file is expected to be an instance of "%s", "%s" given.',
                UploadedFile::class,
                is_object($uploadedFile) ? get_class($uploadedFile) : gettype($uploadedFile)
            ));
        }

        return $this->factory->createUpload($uploadedFile, $forms);
    }
}
