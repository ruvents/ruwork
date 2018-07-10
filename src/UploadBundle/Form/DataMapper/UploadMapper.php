<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\DataMapper;

use Ruwork\UploadBundle\EventListener\FormTerminateListener;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\FormInterface;

final class UploadMapper implements DataMapperInterface
{
    private $manager;
    private $terminateListener;
    private $fileName;
    private $pathName;
    private $factory;
    private $finder;
    private $dataMapper;

    public function __construct(
        UploadManagerInterface $manager,
        FormTerminateListener $terminateListener,
        string $fileName,
        string $pathName,
        callable $factory,
        callable $finder,
        ?DataMapperInterface $dataMapper = null
    ) {
        $this->manager = $manager;
        $this->terminateListener = $terminateListener;
        $this->fileName = $fileName;
        $this->pathName = $pathName;
        $this->factory = $factory;
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
        $fileForm = $formsArray[$this->fileName];
        $pathForm = $formsArray[$this->pathName];

        if (!$fileForm->isEmpty()) {
            $form = $fileForm->getParent();
            $data = ($this->factory)($form, $data);
            $this->manager->register($data, $fileForm->getData());
            $this->terminateListener->registerForm($form);
        } elseif (!$pathForm->isEmpty()) {
            $data = ($this->finder)($pathForm->getData());
        }

        $this->dataMapper->mapFormsToData($forms, $data);
    }
}
