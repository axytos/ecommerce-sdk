<?php

namespace Axytos\ECommerce\Tests\Unit\DependencyInjection;

use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\DependencyInjection\FactoryContainerEntry;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class FactoryContainerEntryTest extends TestCase
{
    /** @var mixed */
    private $factory;

    /** @var Container|MockObject */
    private $container;

    /**
     * @var FactoryContainerEntry
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
        $this->container = $this->createMock(Container::class);

        $this->factory = function (Container $container) {
            return $container->get(FactoryContainerEntry::class);
        };

        $this->sut = new FactoryContainerEntry($this->factory);
    }

    /**
     * @return void
     */
    public function test_get_instance_returns_instance()
    {
        $instance = new \stdClass();

        $this->container
            ->method('get')
            ->with(FactoryContainerEntry::class)
            ->willReturn($instance)
        ;

        $actual = $this->sut->getInstance($this->container);

        $this->assertSame($instance, $actual);
    }
}
