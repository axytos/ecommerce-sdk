<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<ShippingBasketPositionDto>
 */
class ShippingBasketPositionDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<ShippingBasketPositionDto>
     */
    public static function getElementClass(): string
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