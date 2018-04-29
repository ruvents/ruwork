<?php

declare(strict_types=1);

namespace Ruwork\FeatureBundle;

interface FeatureCheckerInterface
{
    public function isAvailable(string $name): bool;
}
