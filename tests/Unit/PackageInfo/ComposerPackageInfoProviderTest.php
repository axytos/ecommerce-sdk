<?php

namespace Axytos\ECommerce\Tests\Unit\PackageInfo;

use Axytos\ECommerce\PackageInfo\ComposerPackageInfoProvider;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ComposerPackageInfoProviderTest extends TestCase
{
    /**
     * @var ComposerPackageInfoProvider
     */
    private $sut;

    /**
     * @return void
     *
     * @before
     */
    #[Before]
    public function beforeEach()
    {
        $this->sut = new ComposerPackageInfoProvider();
    }

    /**
     * @return string
     */
    public function getComposerPackageName()
    {
        /** @var string */
        $composerJson = file_get_contents(__DIR__ . '/../../../composer.json');
        /** @var string[] */
        $config = json_decode($composerJson, true);

        return $config['name'];
    }

    /**
     * @return void
     */
    public function test_is_installed_phpunit()
    {
        $this->assertTrue($this->sut->isInstalled('phpunit/phpunit'));
    }

    /**
     * @return void
     */
    public function test_is_installed_returns_true_for_actual_plugin_package_name()
    {
        $packageName = $this->getComposerPackageName();

        $this->assertTrue($this->sut->isInstalled($packageName));
    }

    /**
     * @return void
     */
    public function test_is_installed_returns_false_if_package_does_not_exist()
    {
        $this->assertFalse($this->sut->isInstalled('does-not-exist'));
    }

    /**
     * @return void
     */
    public function test_get_version_phpunit()
    {
        $version = $this->sut->getVersion('phpunit/phpunit');

        $this->assertNotNull($version);
        $this->assertTrue(is_string($version));
        $this->assertNotEmpty($version);
    }

    /**
     * @return void
     */
    public function test_get_version_returns_not_null_for_actual_plugin_package_name()
    {
        $packageName = $this->getComposerPackageName();

        $this->assertNotNull($this->sut->getVersion($packageName));
    }

    /**
     * @return void
     */
    public function test_get_version_throws_out_of_bounds_exception_if_package_does_not_exist()
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->assertNotNull($this->sut->getVersion('does-not-exist'));
    }
}
