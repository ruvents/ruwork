<?php
declare(strict_types=1);

namespace Ruwork\UploadBundle\Form\Type;

use Ruwork\UploadBundle\Form\DataMapper\CallbackUploadFactory;
use Ruwork\UploadBundle\Form\DataMapper\UploadDataMapper;
use Ruwork\UploadBundle\Form\DataMapper\UploadFactoryInterface;
use Ruwork\UploadBundle\UploadManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadType extends AbstractType
{
    private $manager;

    public function __construct(UploadManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uploadedFile', FileType::class, [
                'mapped' => false,
            ])
            ->setDataMapper(
                new UploadDataMapper($this->manager, $options['factory'], $builder->getDataMapper())
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'empty_data' => null,
            ])
            ->setRequired([
                'factory',
            ])
            ->setAllowedTypes('factory', ['callable', UploadFactoryInterface::class])
            ->setNormalizer('factory', function (Options $options, $factory) {
                if (is_callable($factory)) {
                    $factory = new CallbackUploadFactory($factory);
                }

                return $factory;
            });
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_upload';
    }
}
