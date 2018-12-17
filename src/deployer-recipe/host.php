<?php

declare(strict_types=1);

namespace Deployer;

use Deployer\Task\Context;

set('host', static function (): string {
    return (string) Context::get()->getHost();
});
