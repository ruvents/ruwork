<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Twig;

use Ruwork\AdminBundle\Config\Model\Config;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ConfigExtension extends AbstractExtension
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('ruwork_admin_config', function () {
                return $this->config;
            }),
        ];
    }
}
