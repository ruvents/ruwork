<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Author;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class SecurityTokenAuthorProvider implements AuthorProviderInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthor(ClassMetadata $metadata, string $property)
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();

        if ($metadata->hasAssociation($property)) {
            $class = $metadata->getAssociationTargetClass($property);

            if ($user instanceof $class) {
                return $user;
            }

            return null;
        }

        if ($user instanceof UserInterface) {
            return $user->getUsername();
        }

        if (\is_string($user) || (\is_object($user) && \method_exists($user, '__toString'))) {
            return (string) $user;
        }

        return null;
    }
}
