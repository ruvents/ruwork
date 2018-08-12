<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\DataMapper;

use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\FormInterface;

final class UploadMapper implements DataMapperInterface
{
    private $manager;
    private $sourceName;
    private $pathName;
    private $factory;
    private $finder;
    private $onCreated;
    private $dataMapper;

    public function __construct(
        UploadManagerInterface $manager,
        string $fileName,
        string $pathName,
        callable $factory,
        callable $finder,
        ?callable $onCreated = null,
        ?DataMapperInterface $dataMapper = null
    ) {
        $this->manager = $manager;
        $this->sourceName = $fileName;
        $this->pathName = $pathName;
        $this->factory = $factory;
        $this->finder = $finder;
        $this->onCreated = $onCreated;
        $this->dataMapper = $dataMapper ?? new PropertyPathMapper();
    }

    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($upload, $forms)
    {
        $this->dataMapper->mapDataToForms($upload, $forms);
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$upload)
    {
        /** @var FormInterface[] $formsArray */
        $formsArray = \iterator_to_array($forms);
        $sourceForm = $formsArray[$this->sourceName];
        $pathForm = $formsArray[$this->pathName];

        if (!$sourceForm->isEmpty()) {
            $form = $sourceForm->getParent();
            $upload = ($this->factory)($form, $upload);
            $this->manager->register($upload, $sourceForm->getData());

            if (null !== $this->onCreated) {
                ($this->onCreated)($upload);
            }
        } elseif (!$pathForm->isEmpty()) {
            $upload = ($this->finder)($pathForm->getData());
        }

        $this->dataMapper->mapFormsToData($forms, $upload);
    }
}
