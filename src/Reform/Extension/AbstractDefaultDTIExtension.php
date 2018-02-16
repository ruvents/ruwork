<?php

declare(strict_types=1);

namespace Ruwork\Reform\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractDefaultDTIExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('input', 'datetime_immutable');
    }
}
