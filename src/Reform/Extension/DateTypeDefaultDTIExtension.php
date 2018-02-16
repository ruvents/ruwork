<?php

declare(strict_types=1);

namespace Ruwork\Reform\Extension;

use Symfony\Component\Form\Extension\Core\Type\DateType;

final class DateTypeDefaultDTIExtension extends AbstractDefaultDTIExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return DateType::class;
    }
}
