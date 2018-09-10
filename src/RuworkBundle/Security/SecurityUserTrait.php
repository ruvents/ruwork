<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;

trait SecurityUserTrait
{
    /**
     * @deprecated Deprecated since 0.11.1 and will be removed in 0.12.0.
     * @see        UserInterface::getRoles()
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @see UserInterface::getPassword()
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * @see UserInterface::getSalt()
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @see UserInterface::eraseCredentials()
     */
    public function eraseCredentials()
    {
    }
}
