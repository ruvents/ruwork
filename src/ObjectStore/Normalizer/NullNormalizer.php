<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Normalizer;

final class NullNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($normalized, string $class)
    {
        return $normalized;
    }
}
