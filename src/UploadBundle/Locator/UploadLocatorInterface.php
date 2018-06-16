<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Locator;

interface UploadLocatorInterface
{
    public function locatePath(string $path): string ;

    /**
     * @param object $object
     */
    public function locateUpload($object): string ;
}
