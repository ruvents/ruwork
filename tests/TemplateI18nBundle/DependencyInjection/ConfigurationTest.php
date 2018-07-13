<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testDefault(): void
    {
        $this->assertProcessedConfigurationEquals([], [
            'naming' => [
                'service' => null,
                'locale_suffix_pattern' => '%kernel.default_locale%',
                'extension_pattern' => '\.\w+\.twig',
                'no_suffix_locale' => '%kernel.default_locale%',
            ],
        ]);
    }

    public function testValid(): void
    {
        $this->assertConfigurationIsValid([[
            'naming' => [
                'locale_suffix_pattern' => '%kernel.default_locale%',
                'extension_pattern' => '\.\w+\.twig',
                'no_suffix_locale' => '%kernel.default_locale%',
            ],
        ]]);
    }

    public function testFailIfOtherOptionsPassedWithService(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('If "service" is passed, other options are not accepted.');

        $this->assertConfigurationIsValid([[
            'naming' => [
                'service' => 'service',
                'locale_suffix_pattern' => 'a',
            ],
        ]]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}
