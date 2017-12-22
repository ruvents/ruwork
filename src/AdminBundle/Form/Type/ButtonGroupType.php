<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ButtonGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['ruwork_admin_button_group'] = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_admin_button_group';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return GroupType::class;
    }
}
