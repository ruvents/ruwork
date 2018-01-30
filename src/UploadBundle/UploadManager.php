<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle;

use Ruwork\UploadBundle\Entity\AbstractUpload;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RequestContext;

class UploadManager
{
    private $publicDir;
    private $requestStack;
    private $requestContext;

    public function __construct(string $publicDir, RequestStack $requestStack = null, RequestContext $requestContext = null)
    {
        $this->publicDir = rtrim($publicDir, '/');
        $this->requestStack = $requestStack;
        $this->requestContext = $requestContext;
    }

    public function getPathname(AbstractUpload $upload): string
    {
        return $this->publicDir.'/'.$upload->getPath();
    }

    public function getUrl(AbstractUpload $upload): string
    {
        $path = $upload->getPath();

        if (null !== $this->requestStack && null !== $request = $this->requestStack->getMasterRequest()) {
            return $request->getUriForPath('/'.$path);
        }

        if (null === $this->requestContext || '' === $host = $this->requestContext->getHost()) {
            return $path;
        }

        $scheme = $this->requestContext->getScheme();
        $port = '';

        if ('http' === $scheme && 80 !== $this->requestContext->getHttpPort()) {
            $port = ':'.$this->requestContext->getHttpPort();
        } elseif ('https' === $scheme && 443 !== $this->requestContext->getHttpsPort()) {
            $port = ':'.$this->requestContext->getHttpsPort();
        }

        $baseUrl = rtrim($this->requestContext->getBaseUrl(), '/').'/';

        return $scheme.'://'.$host.$port.$baseUrl.$path;
    }
}
