<?php

declare(strict_types=1);

namespace Ruwork\FeatureBundle;

interface FeatureInterface
{
    public static function getName(): string;

    public function isAvailable(): bool;
}
