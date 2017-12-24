<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\DependencyInjection;

use Ruwork\UploadBundle\Form\TypeGuesser\UploadTypeGuesser;
use Ruwork\UploadBundle\Serializer\UploadNormalizer;
use Ruwork\UploadBundle\UploadManager;
use Ruwork\UploadBundle\Validator\UploadFileValidator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RuworkUploadExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container): void
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        $container->getDefinition(UploadManager::class)
            ->setArgument('$publicDir', $config['public_dir'])
            ->setArgument('$uploadsDirName', $config['uploads_dir_name']);

        if (null !== $config['default_form_type']) {
            $container->findDefinition(UploadTypeGuesser::class)
                ->setArgument('$type', $config['default_form_type']);
        } else {
            $container->removeDefinition(UploadTypeGuesser::class);
        }

        if (!interface_exists(ValidatorInterface::class)) {
            $container->removeDefinition(UploadFileValidator::class);
        }

        if (!interface_exists(NormalizerInterface::class)) {
            $container->removeDefinition(UploadNormalizer::class);
        }
    }
}
