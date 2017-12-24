<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Download;

interface DownloadInterface
{
    public function getDownloadName(): string;
}
