<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\HWIOAuth;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use RunetId\Client\RunetIdClient;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

final class ResourceOwner implements ResourceOwnerInterface
{
    private $client;
    private $name;

    public function __construct(RunetIdClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(HttpRequest $request, $redirectUri, array $extraParameters = [])
    {
        return [
            'access_token' => $request->query->get('token'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInformation(array $accessToken, array $extraParameters = [])
    {
        $result = $this->client
            ->userAuth()
            ->setToken($accessToken['access_token'])
            ->getResult();

        return new UserResponse($this, $result);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationUrl($redirectUri, array $extraParameters = [])
    {
        return $this->client->generateOAuthUri($redirectUri);
    }

    /**
     * {@inheritdoc}
     */
    public function isCsrfTokenValid($csrfToken)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function handles(HttpRequest $request)
    {
        return $request->query->has('token');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name)
    {
        throw new \InvalidArgumentException(\sprintf('Option "%s" does not exist.', $name));
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function addPaths(array $paths)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function refreshAccessToken($refreshToken, array $extraParameters = [])
    {
    }
}
