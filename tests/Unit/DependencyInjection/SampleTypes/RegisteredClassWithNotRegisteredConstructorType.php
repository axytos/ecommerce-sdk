<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\DependencyInjection\SampleTypes;

class RegisteredClassWithNotRegisteredConstructorType
{
    /**
     * @phpstan-ignore-next-line
     */
    public function __construct(
        RegisteredClassInterface $a,
        NotRegisteredClassInterface $b
    ) {
    }
}
