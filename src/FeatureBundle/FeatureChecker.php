<?php

declare(strict_types=1);

namespace Ruwork\FeatureBundle;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Ruwork\FeatureBundle\Exception\NotFoundException;

final class FeatureChecker implements FeatureCheckerInterface
{
    private $features;

    public function __construct(ContainerInterface $features)
    {
        $this->features = $features;
    }

    /**
     * {@inheritdoc}
     */
    public function isAvailable(string $name): bool
    {
        try {
            return $this->features->get($name)->isAvailable();
        } catch (NotFoundExceptionInterface $exception) {
            throw new NotFoundException($name, $exception);
        }
    }
}
