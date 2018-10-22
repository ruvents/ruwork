<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Type;

use Ruwork\ObjectStore\Configurator\StoreConfiguratorInterface;
use Ruwork\ObjectStore\Normalizer\SymfonyNormalizer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SymfonyNormalizerStoreType implements StoreTypeInterface
{
    private $normalizer;
    private $denormalizer;

    public function __construct(NormalizerInterface $normalizer, DenormalizerInterface $denormalizer)
    {
        $this->normalizer = $normalizer;
        $this->denormalizer = $denormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getRequiredTypes(): iterable
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'normalizer_format' => null,
                'normalization_context' => [],
                'denormalization_context' => [],
            ])
            ->setAllowedTypes('normalizer_format', ['null', 'string'])
            ->setAllowedTypes('normalization_context', 'array')
            ->setAllowedTypes('denormalization_context', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function configureStore(StoreConfiguratorInterface $configurator, array $options): void
    {
        $configurator->setNormalizer(new SymfonyNormalizer(
            $this->normalizer,
            $this->denormalizer,
            $options['normalization_context'],
            $options['denormalization_context'],
            $options['normalizer_format']
        ));
    }
}
