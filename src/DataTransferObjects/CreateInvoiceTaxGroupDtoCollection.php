<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<CreateInvoiceTaxGroupDto>
 */
class CreateInvoiceTaxGroupDtoCollection extends DtoCollection
{
    /**
     * @return string
     *
     * @phpstan-return class-string<CreateInvoiceTaxGroupDto>
     */
    public static function getElementClass()
    {
        return CreateInvoiceTaxGroupDto::class;
    }

    /**
     * @phpstan-param CreateInvoiceTaxGroupDto ...$values
     */
    public function __construct(...$values)
    {
        parent::__construct($values);
    }
}
