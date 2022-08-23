<?php declare(strict_types=1);

namespace Axytos\Shopware\Tests\PackageInfo;

use Axytos\ECommerce\PackageInfo\ComposerPackageInfoProvider;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class ComposerPackageInfoProviderTest extends TestCase
{
    private ComposerPackageInfoProvider $sut;

    public function setUp(): void
    {
        $this->sut = new ComposerPackageInfoProvider();
    }

    public function getComposerPackageName(): string
    {
        /** @var string */
        $composerJson = file_get_contents(__DIR__ . '/../../composer.json');
        /** @var string[] */
        $config = json_decode($composerJson, true);

        return $config["name"];
    }

    public function test_isInstalled_returns_true_for_actual_plugin_package_name(): void
    {
        $packageName = $this->getComposerPackageName();

        $this->assertTrue($this->sut->isInstalled($packageName));
    }

    public function test_isInstalled_returns_false_if_package_does_not_exist(): void
    {
        $this->assertFalse($this->sut->isInstalled("does-not-exist"));
    }

    public function test_getVersion_returns_not_null_for_actual_plugin_package_name(): void
    {
        $packageName = $this->getComposerPackageName();

        $this->assertNotNull($this->sut->getVersion($packageName));
    }

    public function test_getVersion_throws_OutOfBoundsException_if_package_does_not_exist(): void
    {
        $this->expectException(OutOfBoundsException::class);

        $this->assertNotNull($this->sut->getVersion("does-not-exist"));
    }
}