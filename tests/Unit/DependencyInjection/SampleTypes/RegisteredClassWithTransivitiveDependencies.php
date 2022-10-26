<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\DependencyInjection\SampleTypes;

class RegisteredClassWithTransivitiveDependencies
{
    /**
     * @phpstan-ignore-next-line
     */
    public function __construct(
        RegisteredClassWithRegisteredConstructorTypes $a,
        RegisteredInstanceInterface $b
    ) {
    }
}
