<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Validator;

use Symfony\Component\Validator\Constraints\Composite as AbstractComposite;

/**
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Composite extends AbstractComposite
{
    public $constraints;

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption(): string
    {
        return 'constraints';
    }

    /**
     * {@inheritdoc}
     */
    protected function getCompositeOption(): string
    {
        return 'constraints';
    }
}
