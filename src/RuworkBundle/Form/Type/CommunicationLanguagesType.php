<?php

namespace Ruvents\RuworkBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommunicationLanguagesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $languageOptions = [
            'label' => false,
        ];

        if ($options['capitalize_choices']) {
            $languageOptions['choice_label'] = function ($value, $label) {
                return mb_strtoupper(mb_substr($label, 0, 1)).mb_substr($label, 1);
            };
        }

        for ($i = 0; $i < $options['number']; $i++) {
            $currentOptions = $languageOptions;

            if (0 === $i) {
                // Make the main language required
                $currentOptions['required'] = true;
                $currentOptions['placeholder'] = 'title.choose_main_communication_language';
            } else {
                $currentOptions['required'] = false;
                $currentOptions['placeholder'] = 'title.choose_additional_communication_language';
            }

            $builder->add($i, LanguageType::class, $currentOptions);
        }

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $event->setData(array_filter($event->getData()));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'label' => 'title.communication_languages',
                'capitalize_choices' => true,
                'number' => 2,
            ])
            ->setAllowedTypes('capitalize_choices', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_communication_languages';
    }
}
