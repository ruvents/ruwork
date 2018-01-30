<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormTypeInterface;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testDefault(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            [
                'public_dir' => '%kernel.project_dir%/public',
                'uploads_dir' => 'uploads',
                'default_form_type' => null,
            ]
        );
    }

    public function testValues(): void
    {
        $this->assertConfigurationIsValid([
            [
                'public_dir' => '%kernel.project_dir%/web',
                'uploads_dir' => 'dir/uploads',
                'default_form_type' => FormType::class,
            ],
        ]);
    }

    public function testEmptyPublicDirError(): void
    {
        $this->assertConfigurationIsInvalid([
            [
                'public_dir' => '',
            ],
        ], 'ruwork_upload.public_dir" cannot contain an empty value');
    }

    public function testEmptyUploadsDirError(): void
    {
        $this->assertConfigurationIsInvalid([
            [
                'uploads_dir' => '',
            ],
        ], 'ruwork_upload.uploads_dir" cannot contain an empty value');
    }

    public function testDefaultFormTypeError(): void
    {
        $this->assertConfigurationIsInvalid([
            [
                'default_form_type' => self::class,
            ],
        ], sprintf('"%s" must implement "%s".', addcslashes(self::class, '\\'), FormTypeInterface::class));
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}
