<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Handler;

use RunetId\Client\Endpoint\Pay\AddEndpoint;
use RunetId\Client\Exception\RunetIdException;
use RunetId\Client\Result\Pay\CouponResult;
use RunetId\Client\Result\Pay\ItemResult;
use Ruwork\RunetIdBundle\Basket\Basket\BasketInterface;
use Ruwork\RunetIdBundle\Basket\Basket\Client;
use Ruwork\RunetIdBundle\Basket\Data\AbstractElementData as Data;
use Ruwork\RunetIdBundle\Basket\Element\AbstractElement as Element;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElementHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(array $options, BasketInterface $basket, Client $client): void
    {
        /**
         * @var Data
         * @var Element $element
         */
        ['data' => $data, 'element' => $element] = $options;

        if ($element->isLocked()) {
            return;
        }

        $this->handleProduct($element, $data, $client);
        $this->handleCoupon($element, $data, $client);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                'data',
                'element',
            ])
            ->setAllowedTypes('data', Data::class)
            ->setAllowedTypes('element', Element::class);
    }

    final protected function handleProduct(Element $element, Data $data, Client $client): void
    {
        $item = $element->getItem();
        $oldProductId = null !== $item ? $item->Product->Id : null;
        $newProductId = $data->getProductId();

        if ($oldProductId === $newProductId) {
            return;
        }

        try {
            if (null !== $oldProductId) {
                $client
                    ->payDelete()
                    ->setOrderItemId($item->Id)
                    ->getRawResult();

                $this->onDeleted($element, $data);
            }
        } catch (RunetIdException $exception) {
            $data->productException = $exception;
            $this->onProductException($element, $data, $exception);

            return;
        }

        try {
            if (null !== $newProductId) {
                $payAdd = $client
                    ->payAdd()
                    ->setOwnerRunetId($element->getRunetId())
                    ->setProductId($newProductId);

                $this->configurePayAdd($payAdd, $element, $data);

                $newItem = $payAdd->getResult();

                $this->onAdded($element, $data, $newItem);
            }
        } catch (RunetIdException $exception) {
            $data->productException = $exception;
            $this->onProductException($element, $data, $exception);

            if (null !== $oldProductId) {
                $client
                    ->payAdd()
                    ->setOwnerRunetId($element->getRunetId())
                    ->setProductId($oldProductId)
                    ->getRawResult();
            }
        }
    }

    final protected function handleCoupon(Element $element, Data $data, Client $client)
    {
        if (!$data->coupon) {
            return;
        }

        try {
            $result = $client
                ->payCoupon()
                ->setOwnerRunetId($element->getRunetId())
                ->setCouponCode($data->coupon)
                ->setProductId($data->getProductId())
                ->getResult();

            $this->onCouponActivated($element, $data, $result);
        } catch (RunetIdException $exception) {
            $data->couponException = $exception;
            $this->onCouponException($element, $data, $exception);
        }
    }

    protected function configurePayAdd(AddEndpoint $endpoint, Element $element, Data $data): void
    {
    }

    protected function onAdded(Element $element, Data $data, ItemResult $item): void
    {
    }

    protected function onDeleted(Element $element, Data $data): void
    {
    }

    protected function onProductException(Element $element, Data $data, RunetIdException $exception): void
    {
    }

    protected function onCouponActivated(Element $element, Data $data, CouponResult $result): void
    {
    }

    protected function onCouponException(Element $element, Data $data, RunetIdException $exception): void
    {
    }
}
