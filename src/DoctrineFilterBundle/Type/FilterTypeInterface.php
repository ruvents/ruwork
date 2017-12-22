<?php

namespace Ruwork\DoctrineFilterBundle\Type;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface FilterTypeInterface
{
    public function createForm(FormFactoryInterface $factory, array $options): FormInterface;

    public function configureOptions(OptionsResolver $resolver);
}
