<?php

namespace Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes;

use Axytos\ECommerce\DataMapping\DtoInterface;

class TestDto3 implements DtoInterface
{
    /**
     * @var string|null
     */
    public $both;
    /**
     * @var string|null
     */
    public $from;
}
