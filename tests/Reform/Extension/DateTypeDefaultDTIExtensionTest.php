<?php

declare(strict_types=1);

namespace Ruwork\Reform\Extension;

use Ruwork\PolyfillFormDTI\DTIExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

class DateTypeDefaultDTIExtensionTest extends FormIntegrationTestCase
{
    public function testInputOptionDefaultsToDTI()
    {
        $form = $this->factory->create(DateType::class);

        $this->assertSame('datetime_immutable', $form->getConfig()->getOption('input'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtensions()
    {
        return [
            new DTIExtension(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTypeExtensions()
    {
        return [
            new DateTypeDefaultDTIExtension(),
        ];
    }
}
