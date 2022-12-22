<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<RefundBasketTaxGroupDto>
 */
class RefundBasketTaxGroupDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<RefundBasketTaxGroupDto>
     * @return string
     */
    public static function getElementClass()
    {
        return RefundBasketTaxGroupDto::class;
    }


    /**
     * @phpstan-param RefundBasketTaxGroupDto ...$values
     */
    public function __construct(...$values)
    {
        parent::__construct($values);
    }
}
