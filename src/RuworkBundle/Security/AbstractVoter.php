<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractVoter extends Voter
{
    private $accessDecisionManager;

    public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    /**
     * @param string|string[] $attributes
     */
    final protected function decide(TokenInterface $token, $attributes, $object = null): bool
    {
        if (!\is_array($attributes)) {
            $attributes = [$attributes];
        }

        return $this->accessDecisionManager->decide($token, $attributes, $object);
    }
}
