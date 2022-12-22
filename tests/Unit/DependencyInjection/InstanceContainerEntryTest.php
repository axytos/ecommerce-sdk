<?php

namespace Axytos\ECommerce\Tests\Unit\DependencyInjection;

use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\DependencyInjection\InstanceContainerEntry;
use PHPUnit\Framework\TestCase;
use stdClass;

class InstanceContainerEntryTest extends TestCase
{
    /**
     * @var \stdClass
     */
    private $instance;

    /**
     * @var \Axytos\ECommerce\DependencyInjection\InstanceContainerEntry
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->instance = new stdClass();

        $this->sut = new InstanceContainerEntry($this->instance);
    }

    /**
     * @return void
     */
    public function test_getInstance_returns_instance()
    {
        $container = $this->createMock(Container::class);

        $actual = $this->sut->getInstance($container);

        $this->assertSame($this->instance, $actual);
    }
}
