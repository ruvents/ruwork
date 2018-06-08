<?php

declare(strict_types=1);

namespace Ruwork\Reform\Mapper;

final class NullSwitchMapper extends AbstractSwitchMapper
{
    /**
     * {@inheritdoc}
     */
    protected function getSwitchValueForData($data, $switchValue)
    {
        return null !== $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDataForSwitchValue($switchValue, $data)
    {
        return $switchValue ? $data : null;
    }
}
