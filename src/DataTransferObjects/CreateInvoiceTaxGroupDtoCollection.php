<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<CreateInvoiceTaxGroupDto>
 */
class CreateInvoiceTaxGroupDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<CreateInvoiceTaxGroupDto>
     */
    public static function getElementClass(): string
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
