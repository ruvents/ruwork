<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle\Config;

use Ruwork\AdminBundle\Config\Model\Config;

class ConfigMetadata
{
    private $config;

    private $dataHash;

    private $fileTimestamps;

    private $passesCount;

    public function __construct(Config $config, string $dataHash, array $fileTimestamps, int $passesCount)
    {
        $this->config = $config;
        $this->dataHash = $dataHash;
        $this->fileTimestamps = $fileTimestamps;
        $this->passesCount = $passesCount;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getDataHash(): string
    {
        return $this->dataHash;
    }

    public function getFileTimestamps(): array
    {
        return $this->fileTimestamps;
    }

    public function getPassesCount(): int
    {
        return $this->passesCount;
    }
}
