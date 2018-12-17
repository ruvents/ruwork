<?php

declare(strict_types=1);

namespace Deployer;

function symfony(string $command): void
{
    run('{{bin/php}} {{release_path}}/bin/console --no-interaction '.$command);
}
