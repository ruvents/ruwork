<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\DependencyInjection;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class RuworkTemplateI18nExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        if (null === $service = $config['naming']['service']) {
            $container->findDefinition('ruwork_template_i18n.naming_strategy')
                ->setArguments([
                    '$localeSuffixPattern' => $config['naming']['locale_suffix_pattern'],
                    '$extensionPattern' => $config['naming']['extension_pattern'],
                    '$noSuffixLocale' => $config['naming']['no_suffix_locale'],
                ]);
        } else {
            $container->findDefinition('ruwork_template_i18n.resolver')
                ->replaceArgument('$namingStrategy', new Reference($service));
        }

        if (!class_exists(Template::class)) {
            $container->removeDefinition('ruwork_template_i18n.annotation_listener');
        }
    }
}
