<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\DependencyInjection;

use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\DependencyInjection\FactoryContainerEntry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class FactoryContainerEntryTest extends TestCase
{
    /** @var mixed */
    private $factory;

    /** @var Container|MockObject $container */
    private Container $container;

    private FactoryContainerEntry $sut;

    public function setUp(): void
    {


        $this->container = $this->createMock(Container::class);

        $this->factory = function (Container $container) {
            return $container->get(FactoryContainerEntry::class);
        };

        $this->sut = new FactoryContainerEntry($this->factory);
    }

    public function test_getInstance_returns_instance(): void
    {
        $instance = new stdClass();

        $this->container
            ->method("get")
            ->with(FactoryContainerEntry::class)
            ->willReturn($instance);

        $actual = $this->sut->getInstance($this->container);

        $this->assertSame($instance, $actual);
    }
}
