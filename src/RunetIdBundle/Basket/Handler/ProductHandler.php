<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Handler;

use RunetId\Client\Exception\RunetIdException;
use RunetId\Client\Result\Pay\ItemResult;
use Ruwork\RunetIdBundle\Basket\Basket\BasketInterface;
use Ruwork\RunetIdBundle\Basket\Basket\Client;
use Ruwork\RunetIdBundle\Basket\Options\NormalizerFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProductHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(array $options, BasketInterface $basket, Client $client): void
    {
        /** @var null|ItemResult $item */
        [
            'exception_handler' => $exceptionHandler,
            'item' => $item,
            'product' => $productId,
            'recover_item' => $recoverItem,
            'user' => $runetId,
        ] = $options;

        $itemProductId = null !== $item ? $item->Product->Id : null;

        if ($itemProductId === $productId) {
            return;
        }

        if (null !== $itemProductId) {
            try {
                $client
                    ->payDelete()
                    ->setOrderItemId($item->Id)
                    ->getRawResult();
            } catch (RunetIdException $exception) {
                $exceptionHandler($exception);

                return;
            }
        }

        if (null === $productId) {
            return;
        }

        try {
            $client
                ->payAdd()
                ->setOwnerRunetId($runetId)
                ->setProductId($productId)
                ->getResult();
        } catch (RunetIdException $exception) {
            if (null !== $itemProductId && $recoverItem) {
                /* @var int $itemProductId */
                $client
                    ->payAdd()
                    ->setOwnerRunetId($runetId)
                    ->setProductId($itemProductId)
                    ->getRawResult();
            }

            $exceptionHandler($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                'user',
            ])
            ->setDefaults([
                'exception_handler' => static function (RunetIdException $exception) {
                    throw $exception;
                },
                'item' => null,
                'product' => null,
                'recover_item' => true,
            ])
            ->setAllowedTypes('exception_handler', 'callable')
            ->setAllowedTypes('item', ['null', ItemResult::class])
            ->setAllowedTypes('product', ['null', 'int', 'object'])
            ->setNormalizer('product', NormalizerFactory::createProductNormalizer())
            ->setAllowedTypes('recover_item', 'bool')
            ->setAllowedTypes('user', ['int', 'object'])
            ->setNormalizer('user', NormalizerFactory::createUserNormalizer());
    }
}
