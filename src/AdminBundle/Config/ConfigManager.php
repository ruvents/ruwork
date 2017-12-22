<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle\Config;

use Psr\SimpleCache\CacheInterface;
use Ruwork\AdminBundle\Config\Model\Config;
use Ruwork\AdminBundle\Config\Pass\PassInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class ConfigManager implements CacheWarmerInterface
{
    private const CACHE_KEY = 'config';

    /**
     * @var array
     */
    private $data;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var iterable|PassInterface[]
     */
    private $passes;

    /**
     * @var null|Config
     */
    private $config;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param array                    $data
     * @param CacheInterface           $cache
     * @param iterable|PassInterface[] $passes
     * @param bool                     $debug
     */
    public function __construct(array $data, CacheInterface $cache, iterable $passes = [], bool $debug = false)
    {
        $this->data = $data;
        $this->cache = $cache;
        $this->passes = $passes;
        $this->debug = $debug;
    }

    public function getConfig(): Config
    {
        if (null !== $this->config) {
            return $this->config;
        }

        if ($this->cache->has(self::CACHE_KEY)) {
            try {
                /** @var ConfigMetadata $metadata */
                $metadata = $this->cache->get(self::CACHE_KEY);

                if (!$this->debug || $this->isFresh($metadata)) {
                    return $this->config = $metadata->getConfig();
                }
            } catch (\Throwable $exception) {
                $this->cache->delete(self::CACHE_KEY);

                throw $exception;
            }
        }

        return $this->config = $this->buildAndSaveConfig();
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $this->buildAndSaveConfig();
    }

    private function isFresh(ConfigMetadata $metadata): bool
    {
        if ($metadata->getDataHash() !== $this->generateHash($this->data)) {
            return false;
        }

        if (count($this->passes) !== $metadata->getPassesCount()) {
            return false;
        }

        foreach ($metadata->getFileTimestamps() as $file => $timestamp) {
            if (@filemtime($file) !== $timestamp) {
                return false;
            }
        }

        return true;
    }

    private function buildAndSaveConfig(): Config
    {
        $config = new Config();
        $hash = $this->generateHash($this->data);
        $fileTimestamps = [
            __FILE__ => filemtime(__FILE__),
        ];

        foreach ($this->passes as $pass) {
            $pass->process($config, $this->data);
            $fileName = (new \ReflectionObject($pass))->getFileName();
            $fileTimestamps[$fileName] = filemtime($fileName);
        }

        foreach ($config->entities as $entityConfig) {
            $fileName = (new \ReflectionClass($entityConfig->class))->getFileName();
            $fileTimestamps[$fileName] = filemtime($fileName);
        }

        $metadata = new ConfigMetadata($config, $hash, $fileTimestamps, count($this->passes));
        $this->cache->set(self::CACHE_KEY, $metadata);

        return $config;
    }

    private function generateHash(array $data)
    {
        return sha1(serialize($data));
    }
}
