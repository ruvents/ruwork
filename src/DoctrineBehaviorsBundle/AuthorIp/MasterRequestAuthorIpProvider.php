<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\AuthorIp;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\HttpFoundation\RequestStack;

final class MasterRequestAuthorIpProvider implements AuthorIpProviderInterface
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorIp(ClassMetadata $metadata, string $property)
    {
        if (null !== $request = $this->requestStack->getMasterRequest()) {
            return $request->getClientIp();
        }

        return null;
    }
}
