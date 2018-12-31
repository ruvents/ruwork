<?php

declare(strict_types=1);

namespace Ruwork\AwsBundle\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Ruwork\AwsBundle\HttpHandler\HttplugHandler;

/**
 * @internal
 */
class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testDefaults(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                'sdks' => [
                    'extra_value' => 'value',
                ],
            ],
            [
                'sdks' => [
                    'default' => [
                        'http_handler' => HttplugHandler::class,
                        'extra_value' => 'value',
                    ],
                ],
                'default_sdk' => 'default',
            ]
        );
    }

    public function testSingleSdkExpanding(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                'sdks' => [
                    'http_handler' => HttplugHandler::class,
                ],
            ],
            [
                'sdks' => [
                    'default' => [
                        'http_handler' => HttplugHandler::class,
                    ],
                ],
                'default_sdk' => 'default',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}
