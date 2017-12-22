<?php

namespace Ruwork\DoctrineFilterBundle\Tests\Fixtures\FilterTypes;

use Doctrine\ORM\QueryBuilder;
use Ruwork\DoctrineFilterBundle\Type\FilterTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterTypeTest implements FilterTypeInterface
{
    const TEST_VALUE = 1;

    public function createForm(FormFactoryInterface $factory, array $options): FormInterface
    {
        return $factory
            ->create()
            ->add('testMethod', TextType::class)
            ->add('test', TextType::class)
            ->submit([
                'testMethod' => self::TEST_VALUE,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function testMethodFilter($data, QueryBuilder $queryBuilder, array $options)
    {
        $queryBuilder->andWhere('a = :data')->setParameter('data', $data);
    }
}
