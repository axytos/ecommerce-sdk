<?php

namespace Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes;

use Axytos\ECommerce\DataMapping\DtoInterface;

class TestDto1 implements DtoInterface
{
    /**
     * @var int|null
     */
    public $both;
    /**
     * @var string|null
     */
    public $differentType;
    /**
     * @var string|null
     */
    public $from;
}
