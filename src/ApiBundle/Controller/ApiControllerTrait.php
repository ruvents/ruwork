<?php

declare(strict_types=1);

namespace Ruwork\ApiBundle\Controller;

use Ruwork\ApiBundle\Helper;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

trait ApiControllerTrait
{
    /**
     * @param string $type
     */
    protected function createFormBuilder($data = null, array $options = [], $type = FormType::class): FormBuilderInterface
    {
        $options['csrf_protection'] = false;
        $options['allow_extra_fields'] = true;

        return $this->getFormFactory()->createNamedBuilder('', $type, $data, $options);
    }

    /**
     * @param null $data
     */
    protected function createForm($type, $data = null, array $options = []): FormInterface
    {
        return $this->createFormBuilder($data, $options, $type)->getForm();
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

        return $this->getNormalizer()->normalize($data, JsonEncoder::FORMAT, $context);
    }

    abstract protected function getNormalizer(): NormalizerInterface;

    abstract protected function getFormFactory(): FormFactoryInterface;
}
