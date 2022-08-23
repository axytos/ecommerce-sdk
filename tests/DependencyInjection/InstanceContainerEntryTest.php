<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\DependencyInjection;

use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\DependencyInjection\InstanceContainerEntry;
use PHPUnit\Framework\TestCase;
use stdClass;

class InstanceContainerEntryTest extends TestCase
{
    private stdClass $instance;

    private InstanceContainerEntry $sut;

    public function setUp(): void
    {
        $this->instance = new stdClass();

        $this->sut = new InstanceContainerEntry($this->instance);
    }
    
    public function test_getInstance_returns_instance(): void
    {
        $container = $this->createMock(Container::class);

        $actual = $this->sut->getInstance($container);

        $this->assertSame($this->instance, $actual);
    }
}
