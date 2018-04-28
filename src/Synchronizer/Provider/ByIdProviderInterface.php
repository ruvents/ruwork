<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Provider;

interface ByIdProviderInterface
{
    /**
     * @param int|float|string $id
     *
     * @return null|mixed
     */
    public function getOneById($id);
}
