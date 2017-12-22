<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Ruwork\LocalePrefixBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testLocalesRequired(): void
    {
        $this->assertConfigurationIsInvalid([
            'ruwork_locale_prefix' => [
                'default_locale' => 'ru',
            ],
        ], 'must be configured');
    }

    public function testLocalesNotEmpty(): void
    {
        $this->assertConfigurationIsInvalid([
            'ruwork_locale_prefix' => [
                'locales' => [],
                'default_locale' => 'ru',
            ],
        ], 'should have at least 1 element');
    }

    public function testLocaleNotEmpty(): void
    {
        $this->assertConfigurationIsInvalid([
            'ruwork_locale_prefix' => [
                'locales' => [''],
                'default_locale' => 'ru',
            ],
        ], 'cannot contain an empty value');
    }

    public function testDefaultLocaleRequired(): void
    {
        $this->assertConfigurationIsInvalid([
            'ruwork_locale_prefix' => [
                'locales' => ['ru'],
            ],
        ], 'must be configured');
    }

    public function testDefaultLocaleNotEmpty(): void
    {
        $this->assertConfigurationIsInvalid([
            'ruwork_locale_prefix' => [
                'locales' => ['ru'],
                'default_locale' => '',
            ],
        ], 'cannot contain an empty value');
    }

    protected function getConfiguration()
    {
        return new Configuration();
    }
}
