<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\Type;

use Ruwork\UploadBundle\Form\DataMapper\UploadMapper;
use Ruwork\UploadBundle\Form\Saver\SaverCollectorInterface;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UploadType extends AbstractType
{
    public const FILE = 'file';
    public const PATH = 'path';

    private $manager;
    private $savers;

    public function __construct(UploadManagerInterface $manager, SaverCollectorInterface $savers)
    {
        $this->manager = $manager;
        $this->savers = $savers;
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
                $this->savers,
                self::FILE,
                self::PATH,
                $options['factory'],
                $options['finder'],
                $options['saver'],
                $builder->getDataMapper()
            ))
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $this->addPath($event->getForm(), $event->getData());
            })
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options): void {
                $form = $event->getForm()->remove(self::PATH);
                $this->addPath($form, $event->getData());
            });
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
                'factory' => function (Options $options): callable {
                    $class = $options['class'];

                    return function () use ($class) {
                        return new $class();
                    };
                },
                'file_type' => FileType::class,
                'file_options' => [],
                'saver' => null,
            ])
            ->setAllowedTypes('class', 'string')
            ->setAllowedTypes('factory', 'callable')
            ->setAllowedTypes('file_options', 'array')
            ->setAllowedTypes('file_type', 'string')
            ->setAllowedTypes('finder', 'callable')
            ->setAllowedTypes('saver', ['null', 'callable']);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_upload';
    }

    private function addPath(FormInterface $form, $upload): void
    {
        $form->add(self::PATH, HiddenType::class, [
            'data' => null === $upload ? null : $this->manager->getPath($upload),
            'mapped' => false,
        ]);
    }
}
