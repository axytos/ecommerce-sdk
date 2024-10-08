<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<RefundBasketPositionDto>
 */
class RefundBasketPositionDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<RefundBasketPositionDto>
     *
     * @return string
     */
    public static function getElementClass()
    {
        return RefundBasketPositionDto::class;
    }

    /**
     * @phpstan-param RefundBasketPositionDto ...$values
     */
    public function __construct(...$values)
    {
        parent::__construct($values);
    }
}
