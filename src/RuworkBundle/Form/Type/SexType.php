<?php

namespace Ruvents\RuworkBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SexType extends AbstractType
{
    const FEMALE = 0;
    const MALE = 1;

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'label' => 'title.sex',
                'expanded' => true,
                'choices' => [
                    'title.male' => self::MALE,
                    'title.female' => self::FEMALE,
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_sex';
    }
}
