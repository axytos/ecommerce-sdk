<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\DependencyInjection\SampleTypes;

class RegisteredClassWithoutConstructorTypes
{
    /**
     * @phpstan-ignore-next-line
     */
    public function __construct($x)
    {
    }
}
