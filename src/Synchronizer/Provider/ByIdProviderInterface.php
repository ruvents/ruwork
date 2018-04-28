<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Provider;

interface ByIdProviderInterface
{
    /**
     * @param float|int|string $id
     *
     * @return null|mixed
     */
    public function getOneById($id);
}
