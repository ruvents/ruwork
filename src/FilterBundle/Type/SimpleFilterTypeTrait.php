<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle\Type;

use Ruwork\FilterBundle\FilterTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

trait SimpleFilterTypeTrait
{
    /**
     * @see FilterTypeInterface::createForm()
     */
    public function createForm(FormFactoryInterface $factory, array $options): FormInterface
    {
        $builder = $factory->createNamedBuilder('', FormType::class, [], [
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'method' => 'GET',
            'validation_groups' => false,
        ]);
        $this->buildForm($builder, $options);

        return $builder->getForm();
    }

    abstract protected function buildForm(FormBuilderInterface $builder, array $options): void;
}
