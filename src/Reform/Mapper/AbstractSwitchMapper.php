<?php

declare(strict_types=1);

namespace Ruwork\Reform\Mapper;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;

abstract class AbstractSwitchMapper implements DataMapperInterface
{
    private $switchName;
    private $mapper;

    public function __construct(string $switchName, DataMapperInterface $mapper = null)
    {
        $this->switchName = $switchName;
        $this->mapper = $mapper ?? new PropertyPathMapper();
    }

    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
        $this->mapper->mapDataToForms($data, $forms);

        foreach ($forms as $name => $form) {
            if ($name === $this->switchName) {
                $form->setData($this->getSwitchValueForData($data, $form->getData()));
                break;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        foreach ($forms as $name => $form) {
            if ($name === $this->switchName) {
                $data = $this->getDataForSwitchValue($form->getData(), $data);
                break;
            }
        }

        $this->mapper->mapFormsToData($forms, $data);
    }

    abstract protected function getSwitchValueForData($data, $switchValue);

    abstract protected function getDataForSwitchValue($switchValue, $data);
}
