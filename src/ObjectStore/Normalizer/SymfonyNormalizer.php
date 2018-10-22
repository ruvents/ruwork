<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface as SymfonyNormalizerInterface;

final class SymfonyNormalizer implements NormalizerInterface
{
    private $normalizer;
    private $denormalizer;
    private $normalizationContext;
    private $denormalizationContext;
    private $format;

    public function __construct(
        SymfonyNormalizerInterface $normalizer,
        DenormalizerInterface $denormalizer,
        array $normalizationContext = [],
        array $denormalizationContext = [],
        ?string $format = null
    ) {
        $this->normalizer = $normalizer;
        $this->denormalizer = $denormalizer;
        $this->normalizationContext = $normalizationContext;
        $this->denormalizationContext = $denormalizationContext;
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data)
    {
        return $this->normalizer->normalize(
            $data,
            $this->format,
            $this->normalizationContext
        );
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($normalized, string $class)
    {
        return $this->denormalizer->denormalize(
            $normalized,
            $class,
            $this->format,
            $this->denormalizationContext
        );
    }
}
