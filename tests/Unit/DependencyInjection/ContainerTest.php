<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\DependencyInjection;

use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\DependencyInjection\ContainerEntryInterface;
use Axytos\ECommerce\DependencyInjection\NotFoundException;
use Axytos\ECommerce\Tests\Unit\DependencyInjection\SampleTypes\RegisteredContainerEntryInterface;
use Axytos\ECommerce\Tests\Unit\DependencyInjection\SampleTypes\NotRegisteredContainerEntryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class ContainerTest extends TestCase
{
    /** @var RegisteredContainerEntryInterface|MockObject $registeredContainerEntry */
    private RegisteredContainerEntryInterface $registeredContainerEntry;

    private Container $sut;

    public function setUp(): void
    {
        $this->registeredContainerEntry = $this->createMock(RegisteredContainerEntryInterface::class);

        $this->sut = new Container([
            RegisteredContainerEntryInterface::class => $this->registeredContainerEntry
        ]);
    }

    public function test_has_returns_true_if_container_entry_exists(): void
    {
        $this->assertTrue($this->sut->has(RegisteredContainerEntryInterface::class));
    }

    public function test_has_returns_false_if_container_entry_does_not_exist(): void
    {
        $this->assertFalse($this->sut->has(NotRegisteredContainerEntryInterface::class));
    }

    public function test_get_returns_instance_from_container_entry_if_container_entry_exists(): void
    {
        $instance = new stdClass();

        $this->registeredContainerEntry
            ->method("getInstance")
            ->with($this->sut)
            ->willReturn($instance);

        $this->assertSame($instance, $this->sut->get(RegisteredContainerEntryInterface::class));
    }

    public function test_get_throws_NotFoundException_if_container_entry_does_not_exist(): void
    {
        $this->expectException(NotFoundException::class);

        $this->sut->get(NotRegisteredContainerEntryInterface::class);
    }
}
