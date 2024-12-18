<?php

namespace Axytos\ECommerce\Tests\Unit\DependencyInjection;

use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\DependencyInjection\NotFoundException;
use Axytos\ECommerce\Tests\Unit\DependencyInjection\SampleTypes\NotRegisteredContainerEntryInterface;
use Axytos\ECommerce\Tests\Unit\DependencyInjection\SampleTypes\RegisteredContainerEntryInterface;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/SampleTypes/NotRegisteredContainerEntryInterface.php';
include_once __DIR__ . '/SampleTypes/RegisteredContainerEntryInterface.php';

/**
 * @internal
 */
class ContainerTest extends TestCase
{
    /** @var RegisteredContainerEntryInterface&MockObject */
    private $registeredContainerEntry;

    /**
     * @var Container
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
        $this->registeredContainerEntry = $this->createMock(RegisteredContainerEntryInterface::class);

        $this->sut = new Container([
            RegisteredContainerEntryInterface::class => $this->registeredContainerEntry,
        ]);
    }

    /**
     * @return void
     */
    public function test_has_returns_true_if_container_entry_exists()
    {
        $this->assertTrue($this->sut->has(RegisteredContainerEntryInterface::class));
    }

    /**
     * @return void
     */
    public function test_has_returns_false_if_container_entry_does_not_exist()
    {
        $this->assertFalse($this->sut->has(NotRegisteredContainerEntryInterface::class));
    }

    /**
     * @return void
     */
    public function test_get_returns_instance_from_container_entry_if_container_entry_exists()
    {
        $instance = new \stdClass();

        $this->registeredContainerEntry
            ->method('getInstance')
            ->with($this->sut)
            ->willReturn($instance)
        ;

        $this->assertSame($instance, $this->sut->get(RegisteredContainerEntryInterface::class));
    }

    /**
     * @return void
     */
    public function test_get_throws_not_found_exception_if_container_entry_does_not_exist()
    {
        $this->expectException(NotFoundException::class);

        $this->sut->get(NotRegisteredContainerEntryInterface::class);
    }
}
