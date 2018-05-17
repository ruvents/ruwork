<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class FrujaxRedirectResponse extends Response
{
    public function __construct(string $location, array $headers = [])
    {
        parent::__construct('', self::HTTP_OK, $headers);
        $this->setRedirectLocation($location);
    }

    public static function createFromRedirect(RedirectResponse $redirect): self
    {
        $frujax = new self('');

        $frujax->version = $redirect->version;
        $frujax->charset = $redirect->charset;
        $frujax->headers = clone $redirect->headers;
        $frujax->headers->remove('Location');

        return $frujax
            ->setRedirectLocation($redirect->getTargetUrl())
            ->setRedirectStatusCode($redirect->getStatusCode());
    }

    public function getRedirectLocation(): string
    {
        return $this->headers->get(FrujaxHeaders::FRUJAX_REDIRECT_LOCATION);
    }

    public function setRedirectLocation(string $location)
    {
        $this->headers->set(FrujaxHeaders::FRUJAX_REDIRECT_LOCATION, $location);

        return $this;
    }

    public function getRedirectStatusCode(): int
    {
        return (int) $this->headers->get(FrujaxHeaders::FRUJAX_REDIRECT_STATUS_CODE);
    }

    public function setRedirectStatusCode(int $statusCode)
    {
        $this->headers->set(FrujaxHeaders::FRUJAX_REDIRECT_STATUS_CODE, $statusCode);

        return $this;
    }
}
