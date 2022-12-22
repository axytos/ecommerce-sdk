<?php

namespace Axytos\ECommerce\PackageInfo;

use Composer\InstalledVersions;

class ComposerPackageInfoProvider
{
    /**
     * @param string $packageName
     * @return bool
     */
    public function isInstalled($packageName)
    {
        return InstalledVersions::isInstalled($packageName);
    }

    /**
     * @param string $packageName
     * @return string|null
     */
    public function getVersion($packageName)
    {
        return InstalledVersions::getVersion($packageName);
    }
}
