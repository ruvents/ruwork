<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Helper;

final class HashHelper
{
    private function __construct()
    {
    }

    public static function generate(string $prefix, string $string, int $maxLength = 20): string
    {
        return substr($prefix.md5($string), 0, $maxLength);
    }
}
