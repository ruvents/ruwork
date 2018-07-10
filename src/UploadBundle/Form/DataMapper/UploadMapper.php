<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\DataMapper;

use Ruwork\UploadBundle\EventListener\FormTerminateListener;
use Ruwork\UploadBundle\Form\Type\UploadType;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\FormInterface;

final class UploadMapper implements DataMapperInterface
{
    private $manager;
    private $terminateListener;
    private $dataMapper;

    public function __construct(
        UploadManagerInterface $manager,
        FormTerminateListener $terminateListener,
        ?DataMapperInterface $dataMapper = null
    ) {
        $this->manager = $manager;
        $this->terminateListener = $terminateListener;
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
        $config = $form->getConfig();

        if (!$fileForm->isEmpty()) {
            $data = $config->getOption('factory')($form, $data);
            $this->manager->register($data, $fileForm->getData());
            $this->terminateListener->registerForm($form);
        } elseif (!$pathForm->isEmpty()) {
            $data = $config->getOption('finder')($pathForm->getData(), $form, $data);
        }

        $this->dataMapper->mapFormsToData($forms, $data);
    }
}
