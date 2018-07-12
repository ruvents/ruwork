<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\HttpKernel\ArgumentValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class CurrentUserValueResolver implements ArgumentValueResolverInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return false;
        }

        if ('currentUser' !== $argument->getName()) {
            return false;
        }

        $type = $argument->getType();

        if (UserInterface::class !== $type && !\is_subclass_of($type, UserInterface::class)) {
            return false;
        }

        $user = $token->getUser();

        return $user instanceof $type;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->tokenStorage->getToken()->getUser();
    }
}
