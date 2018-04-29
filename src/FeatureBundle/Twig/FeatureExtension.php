<?php

declare(strict_types=1);

namespace Ruwork\FeatureBundle\Twig;

use Ruwork\FeatureBundle\FeatureCheckerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FeatureExtension extends AbstractExtension
{
    private $checker;

    public function __construct(FeatureCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('feature', function (string $name): bool {
                return $this->checker->isAvailable($name);
            }),
        ];
    }
}
