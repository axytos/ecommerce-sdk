<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<ShippingBasketPositionDto>
 */
class ShippingBasketPositionDtoCollection extends DtoCollection
{
    /**
     * @return string
     *
     * @phpstan-return class-string<ShippingBasketPositionDto>
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
