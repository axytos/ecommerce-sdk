<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\DependencyInjection;

interface RegisteredClassInterface {}
interface RegisteredInstanceInterface {}
interface NotRegisteredClassInterface {}
interface NotRegisteredInstanceInterface {}

class RegisteredClass implements RegisteredClassInterface {}
class RegisteredInstance implements RegisteredInstanceInterface {}
class NotRegisteredClass implements RegisteredClassInterface {}
class NotRegisteredInstance implements RegisteredInstanceInterface {}

class RegisteredClassWithoutConstructorTypes 
{
    /**
     * @phpstan-ignore-next-line
     */
    public function __construct($x) {}
}

class RegisteredClassWithRegisteredConstructorTypes
{
    /**
     * @phpstan-ignore-next-line
     */
    public function __construct(
        RegisteredClassInterface $a,
        RegisteredInstanceInterface $b) {}
}

class RegisteredClassWithTransivitiveDependencies
{
    /**
     * @phpstan-ignore-next-line
     */
    public function __construct(
        RegisteredClassWithRegisteredConstructorTypes $a,
        RegisteredInstanceInterface $b) {}
}

class RegisteredClassWithNotRegisteredConstructorType
{
    /**
     * @phpstan-ignore-next-line
     */
    public function __construct(
        RegisteredClassInterface $a,
        NotRegisteredClassInterface $b) {}
}
