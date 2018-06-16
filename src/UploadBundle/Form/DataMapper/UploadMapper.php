<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\DataMapper;

use Ruwork\UploadBundle\Form\Type\UploadType;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\FormInterface;

final class UploadMapper implements DataMapperInterface
{
    private $manager;
    private $emptyData;
    private $finder;
    private $dataMapper;

    public function __construct(
        UploadManagerInterface $manager,
        callable $emptyData,
        callable $finder,
        DataMapperInterface $dataMapper = null
    ) {
        $this->manager = $manager;
        $this->emptyData = $emptyData;
        $this->finder = $finder;
        $this->dataMapper = $dataMapper ?? new PropertyPathMapper();
    }

    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
        $this->dataMapper->mapDataToForms($data, $forms);
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        /** @var FormInterface[] $formsArray */
        $formsArray = iterator_to_array($forms);
        $fileForm = $formsArray[UploadType::FILE];
        $pathForm = $formsArray[UploadType::PATH];
        $form = $fileForm->getParent();

        if (!$fileForm->isEmpty()) {
            $data = ($this->emptyData)($form, $data);
            $this->manager->register($data, $fileForm->getData());
        } elseif (!$pathForm->isEmpty()) {
            $data = ($this->finder)($pathForm->getData(), $form, $data);
        }

        $this->dataMapper->mapFormsToData($forms, $data);
    }
}
