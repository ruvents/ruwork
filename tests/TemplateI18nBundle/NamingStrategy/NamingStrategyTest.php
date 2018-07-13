<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\NamingStrategy;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class NamingStrategyTest extends TestCase
{
    /**
     * @dataProvider getNamesAndLocales
     */
    public function testGetLocalizedName(string $name, string $locale, string $expectedName): void
    {
        $strategy = new NamingStrategy('en|fr', '\.(html|xml)\.twig', 'ru');
        $actualName = $strategy->getLocalizedName($name, $locale);

        $this->assertSame($expectedName, $actualName);
    }

    public function getNamesAndLocales(): array
    {
        return [
            ['index.html.twig', 'ru', 'index.html.twig'],
            ['index.html.twig', 'en', 'index.en.html.twig'],
            ['dir/index.xml.twig', 'fr', 'dir/index.fr.xml.twig'],
            ['dir/index.en.xml.twig', 'fr', 'dir/index.fr.xml.twig'],
            ['dir/index.en.css.twig', 'fr', 'dir/index.en.css.twig'],
            ['dir/index.twig', 'en', 'dir/index.twig'],
        ];
    }
}
