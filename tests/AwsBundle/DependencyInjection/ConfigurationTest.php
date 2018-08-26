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

    public function testDefault(): void
    {
        $this->assertProcessedConfigurationEquals([], [
            'http_handler' => HttplugHandler::class,
        ]);
    }

    public function testExtraValues(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'extra' => 'value',
                ],
            ],
            [
                'extra' => 'value',
                'http_handler' => HttplugHandler::class,
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
