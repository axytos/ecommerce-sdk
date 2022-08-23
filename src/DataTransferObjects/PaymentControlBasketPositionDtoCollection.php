<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<PaymentControlBasketPositionDto>
 */
class PaymentControlBasketPositionDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<PaymentControlBasketPositionDto>
     */
    public static function getElementClass(): string
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