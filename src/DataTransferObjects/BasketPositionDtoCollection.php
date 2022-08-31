<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<BasketPositionDto>
 */
class BasketPositionDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<BasketPositionDto>
     */
    public static function getElementClass(): string
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
