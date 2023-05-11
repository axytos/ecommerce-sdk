<?php

namespace Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes;

use Axytos\ECommerce\DataMapping\DtoCollection;
use Axytos\ECommerce\DataMapping\DtoInterface;

/**
 * @phpstan-extends DtoCollection<DtoInterface>
 */
class TestDtoCollection1 extends DtoCollection
{
    /**
     * @phpstan-var class-string<DtoInterface>
     * @var string
     */
    public static $classString;
    /**
     * @phpstan-return class-string<DtoInterface>
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
