<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<CreateInvoiceBasketPositionDto>
 */
class CreateInvoiceBasketPositionDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<CreateInvoiceBasketPositionDto>
     * @return string
     */
    public static function getElementClass()
    {
        return CreateInvoiceBasketPositionDto::class;
    }


    /**
     * @phpstan-param CreateInvoiceBasketPositionDto ...$values
     */
    public function __construct(...$values)
    {
        parent::__construct($values);
    }
}
