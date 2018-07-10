<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Source\Handler;

interface AttributesProviderInterface
{
    public const CLIENT_NAME = 'client_name';
    public const TMP_PATH = 'tmp_path';

    public function getAttributes($source): array;
}
