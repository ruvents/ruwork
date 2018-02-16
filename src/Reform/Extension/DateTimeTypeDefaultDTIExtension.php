<?php

declare(strict_types=1);

namespace Ruwork\Reform\Extension;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

final class DateTimeTypeDefaultDTIExtension extends AbstractDefaultDTIExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return DateTimeType::class;
    }
}
