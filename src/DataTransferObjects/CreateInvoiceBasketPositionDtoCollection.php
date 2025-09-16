<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<CreateInvoiceBasketPositionDto>
 */
class CreateInvoiceBasketPositionDtoCollection extends DtoCollection
{
    /**
     * @return string
     *
     * @phpstan-return class-string<CreateInvoiceBasketPositionDto>
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
