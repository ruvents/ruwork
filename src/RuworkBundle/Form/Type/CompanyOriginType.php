<?php

namespace Ruvents\RuworkBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyOriginType extends AbstractType
{
    const FOREIGN = 0;
    const RUSSIAN = 1;

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'expanded' => true,
            'choices' => [
                'title.russian_company' => self::RUSSIAN,
                'title.foreign_company' => self::FOREIGN,
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
}
