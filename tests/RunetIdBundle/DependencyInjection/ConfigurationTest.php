<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use RunetId\Client\RunetIdClientFactory;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testDefault(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'key' => 'key',
                    'secret' => 'secret',
                ],
            ],
            [
                'clients' => [
                    'default' => [
                        'key' => 'key',
                        'secret' => 'secret',
                        'default_uri' => RunetIdClientFactory::DEFAULT_URI,
                        'plugins' => [],
                        'http_client' => null,
                    ],
                ],
                'default_client' => 'default',
            ]
        );
    }

    public function testMultiple(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'clients' => [
                        2016 => [
                            'key' => 'key',
                            'secret' => 'secret',
                        ],
                        [
                            'name' => 2017,
                            'key' => 'key',
                            'secret' => 'secret',
                            'default_uri' => 'localhost',
                            'plugins' => [
                                'plugin1',
                                'plugin2',
                            ],
                            'http_client' => 'service',
                        ],
                    ],
                    'default_client' => 2016,
                ],
            ],
            [
                'clients' => [
                    2016 => [
                        'key' => 'key',
                        'secret' => 'secret',
                        'default_uri' => RunetIdClientFactory::DEFAULT_URI,
                        'plugins' => [],
                        'http_client' => null,
                    ],
                    2017 => [
                        'key' => 'key',
                        'secret' => 'secret',
                        'default_uri' => 'localhost',
                        'plugins' => [
                            'plugin1',
                            'plugin2',
                        ],
                        'http_client' => 'service',
                    ],
                ],
                'default_client' => 2016,
            ]
        );
    }

    public function testInvalidDefaultClient(): void
    {
        $this->assertConfigurationIsInvalid([[
            'clients' => [
                'some' => [
                    'key' => 'key',
                    'secret' => 'secret',
                ],
            ],
            'default_client' => 2016,
        ]], 'Invalid configuration for path "ruwork_runet_id": Client "2016" is not defined and cannot be used as default.');
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}
