<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\DependencyInjection;

use Ruwork\TemplateI18nBundle\EventListener\TemplateAnnotationListener;
use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategy;
use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategyInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
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
            $container
                ->findDefinition(NamingStrategy::class)
                ->setArguments([
                    '$localeSuffixPattern' => $config['naming']['locale_suffix_pattern'],
                    '$extensionPattern' => $config['naming']['extension_pattern'],
                    '$noSuffixLocale' => $config['naming']['no_suffix_locale'],
                ]);
        } else {
            $container->setAlias(NamingStrategyInterface::class, $service);
        }

        if (!class_exists(Template::class)) {
            $container->removeDefinition(TemplateAnnotationListener::class);
        }
    }
}
