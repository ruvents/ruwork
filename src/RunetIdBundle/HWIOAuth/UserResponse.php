<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\HWIOAuth;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\AbstractUserResponse;
use RunetId\Client\Result\User\UserResult;

final class UserResponse extends AbstractUserResponse
{
    private $userResult;

    public function __construct(ResourceOwnerInterface $resourceOwner, UserResult $userResult)
    {
        $this->resourceOwner = $resourceOwner;
        $this->userResult = $userResult;
    }

    public function getUserResult(): UserResult
    {
        return $this->userResult;
    }

    public function getRunetId(): int
    {
        return $this->userResult->RunetId;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return (string) $this->userResult->RunetId;
    }

    /**
     * {@inheritdoc}
     */
    public function getNickname()
    {
        return (string) $this->userResult->RunetId;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->userResult->Email;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName()
    {
        return $this->userResult->FirstName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName()
    {
        return $this->userResult->LastName;
    }

    /**
     * {@inheritdoc}
     */
    public function getRealName()
    {
        return $this->userResult->FirstName.' '.$this->userResult->LastName;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfilePicture()
    {
        return null === $this->userResult->Photo ? null : $this->userResult->Photo->Original;
    }
}
