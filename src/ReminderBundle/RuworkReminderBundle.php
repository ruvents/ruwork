<?php

declare(strict_types=1);

namespace Ruwork\ReminderBundle;

use Ruwork\Reminder\DependencyInjection\AddProvidersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RuworkReminderBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddProvidersPass());
    }
}
