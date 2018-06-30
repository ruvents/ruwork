<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\Form\TypeExtension;

use Ruwork\FrujaxBundle\HttpFoundation\FrujaxHeaders;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;

final class FrujaxFormTypeExtension extends AbstractTypeExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['root'] = $form->isRoot();
        $view->vars['root_action'] = $view->parent->vars['root_action'] ?? $form->getRoot()->getConfig()->getAction();
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null !== $request && $request->headers->get(FrujaxHeaders::FRUJAX_HIDE_FORM_ERRORS)) {
            $view->vars['errors'] = [];
            $view->vars['valid'] = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}
