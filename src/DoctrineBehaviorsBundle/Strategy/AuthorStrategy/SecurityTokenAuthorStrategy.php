<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Strategy\AuthorStrategy;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityTokenAuthorStrategy implements AuthorStrategyInterface
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
        if ($metadata->hasField($property)) {
            return $this->getFieldAuthor();
        }

        if ($metadata->hasAssociation($property)) {
            return $this->getAssociationAuthor($metadata->getAssociationTargetClass($property));
        }

        throw new \LogicException(sprintf('Property "%s" of class "%s" is neither a mapped field, nor an association.', $property, $metadata->getName()));
    }

    public function getFieldAuthor(): ?string
    {
        if (null !== $token = $this->tokenStorage->getToken()) {
            $user = $token->getUser();

            if ($user instanceof UserInterface) {
                return $user->getUsername();
            }

            if (is_string($user) || (is_object($user) && method_exists($user, '__toString'))) {
                return (string) $user;
            }
        }

        return null;
    }

    /**
     * @return null|object
     */
    public function getAssociationAuthor(string $targetClass)
    {
        if (null !== $token = $this->tokenStorage->getToken()) {
            $user = $token->getUser();

            if ($user instanceof $targetClass) {
                return $user;
            }
        }

        return null;
    }
}
