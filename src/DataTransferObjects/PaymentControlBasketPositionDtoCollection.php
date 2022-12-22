<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<PaymentControlBasketPositionDto>
 */
class PaymentControlBasketPositionDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<PaymentControlBasketPositionDto>
     * @return string
     */
    public static function getElementClass()
    {
        return PaymentControlBasketPositionDto::class;
    }


    /**
     * @phpstan-param PaymentControlBasketPositionDto ...$values
     */
    public function __construct(...$values)
    {
        parent::__construct($values);
    }
}
