<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Doctrine\Repository;

interface UploadFinderInterface
{
    /**
     * @return null|object
     */
    public function findOneByPath(string $path);
}
