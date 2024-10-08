<?php

namespace Axytos\ECommerce\Tests\Unit\DependencyInjection;

use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\DependencyInjection\InstanceContainerEntry;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class InstanceContainerEntryTest extends TestCase
{
    /**
     * @var \stdClass
     */
    private $instance;

    /**
     * @var InstanceContainerEntry
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
        $this->instance = new \stdClass();

        $this->sut = new InstanceContainerEntry($this->instance);
    }

    /**
     * @return void
     */
    public function test_get_instance_returns_instance()
    {
        $container = $this->createMock(Container::class);

        $actual = $this->sut->getInstance($container);

        $this->assertSame($this->instance, $actual);
    }
}
