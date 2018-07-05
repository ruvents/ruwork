<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testDefault(): void
    {
        $this->assertProcessedConfigurationEquals([], [
            'optional_prefix' => false,
            'twig' => [
                'object_as_parameters' => true,
                'routing_helpers' => true,
            ],
        ]);
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }
}
