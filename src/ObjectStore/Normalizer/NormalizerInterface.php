<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Normalizer;

interface NormalizerInterface
{
    /**
     * @param object $object
     */
    public function normalize($object);

    /**
     * @return object
     */
    public function denormalize($normalized, string $class);
}
