<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Form;

use RunetId\Client\Exception\RunetIdException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PropertyAccess\Exception\RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class FormErrorsMapper implements FormErrorsMapperInterface
{
    private const PREFIX = 'ruwork_runet_id.form_errors.';

    private $session;
    private $accessor;

    public function __construct(SessionInterface $session, ?PropertyAccessorInterface $accessor = null)
    {
        $this->session = $session;
        $this->accessor = $accessor ?? PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function save(string $id, $object, $map): void
    {
        if (\is_callable($map)) {
            $map = $map();
        }

        if (!is_iterable($map)) {
            throw new \InvalidArgumentException('Map must be iterable or a callback, returning an iterable value.');
        }

        $data = [];

        foreach ($map as $source => $target) {
            $exception = $this->accessor->getValue($object, $source);

            if (null === $exception) {
                continue;
            }

            if (!$exception instanceof RunetIdException) {
                throw new \UnexpectedValueException(sprintf('Expected null or an instance of "%s".', RunetIdException::class));
            }

            $data[] = [$target, $exception->getMessage()];
        }

        $id = self::PREFIX.$id;
        $this->session->set($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function apply(string $id, FormInterface $form): void
    {
        $id = self::PREFIX.$id;
        $data = $this->session->get($id, []);
        $this->session->remove($id);

        foreach ($data as [$target, $message]) {
            try {
                $target = $this->accessor->getValue($form, $target);
            } catch (RuntimeException $exception) {
                continue;
            }

            if ($target instanceof FormInterface) {
                $target->addError(new FormError($message));
            }
        }
    }
}
