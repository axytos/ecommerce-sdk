<?php

namespace Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes;

use Axytos\ECommerce\DataMapping\DtoCollection;
use Axytos\ECommerce\DataMapping\DtoInterface;

class TestDtoCollection2 extends DtoCollection
{
    /**
     * @phpstan-var class-string<DtoInterface>
     * @var string
     */
    public static $classString;
    /**
     * @return string
     */
    public static function getElementClass()
    {
        return self::$classString;
    }
    public function __construct(DtoInterface ...$values)
    {
        parent::__construct($values);
    }
}
