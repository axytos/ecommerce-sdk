<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<ShippingBasketPositionDto>
 */
class ShippingBasketPositionDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<ShippingBasketPositionDto>
     * @return string
     */
    public static function getElementClass()
    {
        return ShippingBasketPositionDto::class;
    }


    /**
     * @phpstan-param ShippingBasketPositionDto ...$values
     */
    public function __construct(...$values)
    {
        parent::__construct($values);
    }
}
