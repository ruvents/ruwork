<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Options;

use Symfony\Component\OptionsResolver\Options;

final class NormalizerFactory
{
    private function __construct()
    {
    }

    public static function createProductNormalizer(): \Closure
    {
        return static function (Options $options, $product): ?int {
            if (\is_object($product)) {
                if (!method_exists($product, 'getId')) {
                    throw new \InvalidArgumentException(sprintf('Product of class "%s" must have a "getId(): int" method.', \get_class($product)));
                }

                return $product->getId();
            }

            return $product;
        };
    }

    public static function createUserNormalizer(): \Closure
    {
        return static function (Options $options, $runetId): int {
            if (\is_object($runetId)) {
                if (!method_exists($runetId, 'getRunetId')) {
                    throw new \InvalidArgumentException(sprintf('User of class "%s" must have a "getRunetId(): int" method.', \get_class($runetId)));
                }

                return $runetId->getRunetId();
            }

            return $runetId;
        };
    }
}
