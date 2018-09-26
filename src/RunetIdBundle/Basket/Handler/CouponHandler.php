<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Handler;

use RunetId\Client\Exception\RunetIdException;
use Ruwork\RunetIdBundle\Basket\Basket\BasketInterface;
use Ruwork\RunetIdBundle\Basket\Basket\Client;
use Ruwork\RunetIdBundle\Basket\Options\NormalizerFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CouponHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(array $options, BasketInterface $basket, Client $client): void
    {
        try {
            $client
                ->payCoupon()
                ->setOwnerRunetId($options['user'])
                ->setCouponCode($options['coupon'])
                ->setProductId($options['product'])
                ->getResult();
        } catch (RunetIdException $exception) {
            $options['exception_handler']($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                'coupon',
                'user',
            ])
            ->setDefaults([
                'exception_handler' => static function (RunetIdException $exception) {
                    throw $exception;
                },
                'product' => null,
            ])
            ->setAllowedTypes('coupon', 'string')
            ->setAllowedTypes('exception_handler', 'callable')
            ->setAllowedTypes('product', ['null', 'int', 'object'])
            ->setNormalizer('product', NormalizerFactory::createProductNormalizer())
            ->setAllowedTypes('user', ['int', 'object'])
            ->setNormalizer('user', NormalizerFactory::createUserNormalizer());
    }
}
