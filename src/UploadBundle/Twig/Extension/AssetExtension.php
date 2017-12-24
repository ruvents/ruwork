<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\Twig\Extension;

use Ruwork\UploadBundle\Entity\AbstractUpload;
use Symfony\Bridge\Twig\Extension\AssetExtension as BaseAssetExtension;

class AssetExtension extends BaseAssetExtension
{
    /**
     * {@inheritdoc}
     */
    public function getAssetUrl($path, $packageName = null)
    {
        if ($path instanceof AbstractUpload) {
            $path = $path->getPath();
        }

        return parent::getAssetUrl($path, $packageName);
    }

    /**
     * {@inheritdoc}
     */
    public function getAssetVersion($path, $packageName = null)
    {
        if ($path instanceof AbstractUpload) {
            $path = $path->getPath();
        }

        return parent::getAssetVersion($path, $packageName);
    }
}
