<?php

declare(strict_types=1);

namespace Ruwork\ApiBundle\Controller;

use Psr\Container\ContainerInterface;
use Ruwork\ApiBundle\Helper;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * @property ContainerInterface $container
 */
trait ApiControllerTrait
{
    protected function createApiFormBuilder(
        $data = null,
        array $options = [],
        string $type = FormType::class
    ): FormBuilderInterface {
        $options['csrf_protection'] = false;
        $options['allow_extra_fields'] = true;

        return $this->container
            ->get('form.factory')
            ->createNamedBuilder('', $type, $data, $options);
    }

    protected function createApiForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->createApiFormBuilder($data, $options, $type)->getForm();
    }

    /**
     * @deprecated since 0.13 and will be removed in 0.14
     */
    protected function createFormBuilder(
        $data = null,
        array $options = [],
        string $type = FormType::class
    ): FormBuilderInterface {
        @trigger_error(
            sprintf('Method "%s" is deprecated since 0.13 and will be removed in 0.14', __METHOD__),
            E_USER_DEPRECATED
        );

        return $this->createApiFormBuilder($data, $options, $type);
    }

    /**
     * @deprecated since 0.13 and will be removed in 0.14
     */
    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        @trigger_error(
            sprintf('Method "%s" is deprecated since 0.13 and will be removed in 0.14', __METHOD__),
            E_USER_DEPRECATED
        );

        return $this->createApiForm($type, $data, $options);
    }

    protected function validateForm(FormInterface $form): void
    {
        if (!$form->isSubmitted()) {
            $form->submit(null);
        }

        if (!$form->isValid()) {
            $message = '';

            foreach ($form->getErrors(true) as $error) {
                $path = '';

                if (null !== $propertyPath = $error->getOrigin()->getPropertyPath()) {
                    $path = \implode('.', $propertyPath->getElements());
                }

                $message .= $path.': '.$error->getMessage()."\n";
            }

            throw new BadRequestHttpException(\trim($message));
        }
    }

    protected function normalize($data, array $context = [])
    {
        $context[Helper::RUWORK_API] = true;

        return $this->container
            ->get('serializer')
            ->normalize($data, JsonEncoder::FORMAT, $context);
    }
}
