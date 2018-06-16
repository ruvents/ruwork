<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

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
            ]
        );
    }

    public function testValues(): void
    {
        $this->assertConfigurationIsValid([
            [
                'public_dir' => '%kernel.project_dir%/web',
                'uploads_dir' => 'dir/uploads',
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

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}
