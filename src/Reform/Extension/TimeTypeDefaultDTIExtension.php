<?php

declare(strict_types=1);

namespace Ruwork\Reform\Extension;

use Symfony\Component\Form\Extension\Core\Type\TimeType;

final class TimeTypeDefaultDTIExtension extends AbstractDefaultDTIExtension
{
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [TimeType::class];
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return TimeType::class;
    }
}
