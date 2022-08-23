<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<CreateInvoiceBasketPositionDto>
 */
class CreateInvoiceBasketPositionDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<CreateInvoiceBasketPositionDto>
     */
    public static function getElementClass(): string
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
