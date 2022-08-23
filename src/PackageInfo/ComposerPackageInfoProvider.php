<?php declare(strict_types=1);

namespace Axytos\ECommerce\PackageInfo;

use Composer\InstalledVersions;

class ComposerPackageInfoProvider
{
    public function isInstalled(string $packageName): bool
    {
        return InstalledVersions::isInstalled($packageName);
    }

    public function getVersion(string $packageName): ?string
    {
        return InstalledVersions::getVersion($packageName);
    }
}