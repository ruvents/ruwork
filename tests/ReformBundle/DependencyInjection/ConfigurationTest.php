<?php

declare(strict_types=1);

namespace Ruwork\ReformBundle\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testDefault(): void
    {
        $this->assertProcessedConfigurationEquals([], [
            'extensions' => [
                'novalidate' => true,
                'default_datetime_immutable' => true,
            ],
        ]);
    }

    public function testValues(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'extensions' => [
                        'novalidate' => false,
                        'default_datetime_immutable' => false,
                    ],
                ],
            ],
            [
                'extensions' => [
                    'novalidate' => false,
                    'default_datetime_immutable' => false,
                ],
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
