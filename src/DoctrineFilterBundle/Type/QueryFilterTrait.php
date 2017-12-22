<?php

namespace Ruwork\DoctrineFilterBundle\Type;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

trait QueryFilterTrait
{
    /**
     * @see FilterTypeInterface::createForm()
     *
     * @param FormFactoryInterface $factory
     * @param array                $options
     *
     * @return FormInterface
     */
    public function createForm(FormFactoryInterface $factory, array $options): FormInterface
    {
        $builder = $factory->createNamedBuilder('', FormType::class, null, ['method' => 'GET']);
        $this->buildForm($builder, $options);

        return $builder->getForm();
    }

    abstract protected function buildForm(FormBuilderInterface $builder, array $options): void;
}
