<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<BasketPositionDto>
 */
class BasketPositionDtoCollection extends DtoCollection
{
    /**
     * @return string
     *
     * @phpstan-return class-string<BasketPositionDto>
     */
    public static function getElementClass()
    {
        return BasketPositionDto::class;
    }

    /**
     * @phpstan-param BasketPositionDto ...$values
     */
    public function __construct(...$values)
    {
        parent::__construct($values);
    }
}
