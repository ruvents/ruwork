<?php

declare(strict_types=1);

namespace Ruwork\Reform\Extension;

use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

/**
 * @internal
 */
class TimeTypeDefaultDTIExtensionTest extends FormIntegrationTestCase
{
    public function testInputOptionDefaultsToDTI()
    {
        $form = $this->factory->create(TimeType::class);

        $this->assertSame('datetime_immutable', $form->getConfig()->getOption('input'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getTypeExtensions()
    {
        return [
            new TimeTypeDefaultDTIExtension(),
        ];
    }
}
