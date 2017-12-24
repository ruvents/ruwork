<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButtonTypeInterface;

class DeleteType extends AbstractType implements SubmitButtonTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_admin_delete';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return SubmitType::class;
    }
}
