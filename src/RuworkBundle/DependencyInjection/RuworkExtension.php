<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\DependencyInjection;

use Ruwork\RuworkBundle\EventListener\I18nControllerTemplateListener;
use Ruwork\RuworkBundle\Mailer\Mailer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class RuworkExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        if ($config['i18n']['enabled']) {
            if ($config['i18n']['suffix_controller_templates']) {
                $container->autowire(I18nControllerTemplateListener::class)
                    ->setAutoconfigured(true)
                    ->setPublic(false)
                    ->setArgument('$locales', $config['i18n']['locales'])
                    ->setArgument('$defaultLocale', $config['i18n']['default_locale']);
            }
        }

        $container->findDefinition(Mailer::class)
            ->setArgument('$users', $config['mailer']['users']);
    }
}
