<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Type;

use Ruwork\ObjectStore\Configurator\StoreConfiguratorInterface;
use Ruwork\ObjectStore\Storage\SymfonySessionStorage;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SymfonySessionStoreType implements StoreTypeInterface
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public static function getRequiredTypes(): iterable
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('session_key')
            ->setAllowedTypes('session_key', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function configureStore(StoreConfiguratorInterface $configurator, array $options): void
    {
        $configurator->setStorage(new SymfonySessionStorage($this->session, $options['session_key']));
    }
}
