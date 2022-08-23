<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\DependencyInjection;

use Axytos\ECommerce\DependencyInjection\ClassContainerEntry;
use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\DependencyInjection\MissingConstructorParameterTypeException;
use Axytos\ECommerce\DependencyInjection\NotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

include_once __DIR__.'/example_types.php';

class ClassContainerEntryTest extends TestCase
{
    /** @var Container|MockObject $container */
    private Container $container;

    public function setUp(): void
    {
        $this->container = $this->createMock(Container::class); 

        $this->container
            ->method("get")
            ->willReturnMap([
                [RegisteredClassInterface::class, new RegisteredClass()],
                [RegisteredInstanceInterface::class, new RegisteredInstance()]
            ]);
    }
    
    public function test_getInstance_throws_MissingConstructorParameterTypeException_for_class_without_constructor_types(): void
    {
        $this->expectException(MissingConstructorParameterTypeException::class);

        $containerEntry = new ClassContainerEntry(RegisteredClassWithoutConstructorTypes::class);

        $containerEntry->getInstance($this->container);
    }
    
    public function test_getInstance_throws_NotFoundException_for_class_with_not_registered_constructor_type(): void
    {
        $this->expectException(NotFoundException::class);

        $this->container
            ->method("get")
            ->willThrowException(new NotFoundException("id"));

        $containerEntry = new ClassContainerEntry(RegisteredClassWithNotRegisteredConstructorType::class);

        $containerEntry->getInstance($this->container);
    }

    public function test_getInstance_returns_instance_for_class_without_constructor(): void
    {
        $containerEntry = new ClassContainerEntry(RegisteredClass::class);

        $actual = $containerEntry->getInstance($this->container);

        $this->assertInstanceOf(RegisteredClass::class, $actual);
    }

    public function test_getInstance_returns_instance_for_class_with_registered_constructor_types(): void
    {
        $containerEntry = new ClassContainerEntry(RegisteredClassWithRegisteredConstructorTypes::class);

        $actual = $containerEntry->getInstance($this->container);

        $this->assertInstanceOf(RegisteredClassWithRegisteredConstructorTypes::class, $actual);
    }
}
