<?php
declare(strict_types=1);

namespace Ruwork\UploadBundle;

use Ruwork\UploadBundle\Entity\AbstractUpload;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RequestContext;

class UploadManager
{
    private $publicDir;

    private $uploadsDirName;

    private $requestStack;

    private $requestContext;

    public function __construct(string $publicDir, string $uploadsDirName, RequestStack $requestStack = null, RequestContext $requestContext = null)
    {
        $this->publicDir = rtrim($publicDir, '/');
        $this->uploadsDirName = trim($uploadsDirName, '/');
        $this->requestStack = $requestStack;
        $this->requestContext = $requestContext;
    }

    public function getUploadsDir(): string
    {
        return $this->publicDir.'/'.$this->uploadsDirName;
    }

    public function getPathname(AbstractUpload $upload): string
    {
        return $this->publicDir.'/'.$upload->getPath();
    }

    public function generatePath(string $extension = null): string
    {
        $random = bin2hex(random_bytes(16));

        return $this->uploadsDirName
            .'/'.substr($random, 0, 2)
            .'/'.substr($random, 2)
            .($extension ? '.'.$extension : '');
    }

    public function saveUpload(AbstractUpload $upload): void
    {
        $uploadedFile = $upload->getUploadedFile();

        if (null === $uploadedFile) {
            throw new \UnexpectedValueException(sprintf('Cannot save upload "%s" because it has an empty uploaded file.', get_class($upload)));
        }

        $path = $upload->getPath();
        $uploadedFile->move($this->publicDir.'/'.dirname($path), basename($path));
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
