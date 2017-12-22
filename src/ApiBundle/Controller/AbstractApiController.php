<?php

namespace Ruwork\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class AbstractApiController extends AbstractController
{
    use ApiControllerTrait;

    /**
     * {@inheritdoc}
     */
    protected function getNormalizer(): NormalizerInterface
    {
        return $this->container->get('serializer');
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormFactory(): FormFactoryInterface
    {
        return $this->container->get('form.factory');
    }
}
