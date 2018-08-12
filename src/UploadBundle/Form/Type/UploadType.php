<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\Type;

use Ruwork\UploadBundle\Form\DataMapper\UploadMapper;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UploadType extends AbstractType
{
    public const FILE = 'file';
    public const PATH = 'path';

    private $manager;

    public function __construct(UploadManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FILE, $options['file_type'], ['mapped' => false] + $options['file_options'])
            ->setDataMapper(new UploadMapper(
                $this->manager,
                self::FILE,
                self::PATH,
                $options['factory'],
                $options['finder'],
                $options['on_created'],
                $builder->getDataMapper()
            ))
            ->addEventListener(FormEvents::POST_SET_DATA, [$this, 'addPath'])
            ->addEventListener(FormEvents::SUBMIT, [$this, 'addPath']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                'class',
                'finder',
            ])
            ->setDefaults([
                'empty_data' => null,
                'error_bubbling' => false,
                'factory' => function (Options $options): \Closure {
                    $class = $options['class'];

                    return static function () use ($class) {
                        return new $class();
                    };
                },
                'file_type' => FileType::class,
                'file_options' => [],
                'on_created' => null,
            ])
            ->setAllowedTypes('class', 'string')
            ->setAllowedTypes('factory', 'callable')
            ->setAllowedTypes('file_options', 'array')
            ->setAllowedTypes('file_type', 'string')
            ->setAllowedTypes('finder', 'callable')
            ->setAllowedTypes('on_created', ['null', 'callable']);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_upload';
    }

    public function addPath(FormEvent $event): void
    {
        $form = $event->getForm();
        $upload = $form->getData();

        $form->add(self::PATH, HiddenType::class, [
            'data' => null === $upload ? null : $this->manager->getPath($upload),
            'mapped' => false,
        ]);
    }
}
