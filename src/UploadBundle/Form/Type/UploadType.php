<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\Type;

use Ruwork\UploadBundle\Form\DataMapper\UploadMapper;
use Ruwork\UploadBundle\Manager\UploadManagerInterface;
use Ruwork\UploadBundle\Metadata\UploadAccessor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UploadType extends AbstractType
{
    public const ATTRIBUTE = 'ruwork_upload_form';
    public const FILE = 'file';
    public const PATH = 'path';

    private $manager;
    private $accessor;

    public function __construct(UploadManagerInterface $manager, UploadAccessor $accessor)
    {
        $this->manager = $manager;
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setDataMapper(new UploadMapper(
                $this->manager,
                $options['empty_data'],
                $options['upload_finder'],
                $builder->getDataMapper()
            ))
            ->setAttribute(self::ATTRIBUTE, true)
            ->add(self::FILE, $options['file_type'], ['mapped' => false] + $options['file_options'])
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
                'data_class',
                'upload_finder',
            ])
            ->setDefaults([
                'error_bubbling' => false,
                'error_mapping' => [
                    '.' => self::FILE,
                ],
                'label' => false,
                'required' => false,
                'file_type' => FileType::class,
                'file_options' => [],
                'submit_handler' => function () {
                },
            ])
            ->setAllowedTypes('empty_data', 'callable')
            ->setAllowedTypes('file_type', 'string')
            ->setAllowedTypes('file_options', 'array')
            ->setAllowedTypes('upload_finder', 'callable')
            ->setAllowedTypes('submit_handler', 'callable');
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
            'data' => null === $upload ? null : $this->accessor->getPath($upload),
            'mapped' => false,
        ]);
    }
}
