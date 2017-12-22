<?php

namespace Ruwork\ApiBundle\Normalizer;

use Ruwork\ApiBundle\Helper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

abstract class AbstractApiNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * {@inheritdoc}
     */
    final public function supportsNormalization($data, $format = null, array $context = [])
    {
        return ($context[Helper::RUWORK_API] ?? false) && $this->supportsApiNormalization($data, $format, $context);
    }

    abstract protected function supportsApiNormalization($data, $format = null, array $context = []): bool;
}
