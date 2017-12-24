<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Config\Pass;

use Ruwork\AdminBundle\Config\Model\Config;

class ResolveFormThemePass implements PassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(Config $config, array $data): void
    {
        $defaultTheme = $data['forms']['default_theme'];

        foreach ($config->entities as $entity) {
            if (null === $entity->create->theme) {
                $entity->create->theme = $defaultTheme;
            }

            if (null === $entity->edit->theme) {
                $entity->edit->theme = $defaultTheme;
            }
        }
    }
}
