<?php

namespace Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes;

use Axytos\ECommerce\DataMapping\DtoInterface;

class TestDto2 implements DtoInterface
{
    /**
     * @var int|null
     */
    public $both;
    /**
     * @var int|null
     */
    public $differentType;
    /**
     * @var string|null
     */
    public $to;
}
