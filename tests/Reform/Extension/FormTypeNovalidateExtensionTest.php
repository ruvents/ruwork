<?php

declare(strict_types=1);

namespace Ruwork\Reform\Extension;

use Symfony\Component\Form\Test\FormIntegrationTestCase;

class FormTypeNovalidateExtensionTest extends FormIntegrationTestCase
{
    public function testNovalidateAddedToRootForm()
    {
        $view = $this->factory->create()->createView();

        $this->assertTrue($view->vars['attr']['novalidate']);
    }

    public function testNovalidateNotAddedToChildForm()
    {
        $view = $this->factory
            ->createBuilder()
            ->add('child')
            ->getForm()
            ->createView();

        $this->assertArrayNotHasKey('novalidate', $view['child']->vars['attr']);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTypeExtensions()
    {
        return [
            new FormTypeNovalidateExtension(),
        ];
    }
}
