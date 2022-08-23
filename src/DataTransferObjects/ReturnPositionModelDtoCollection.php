<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<ReturnPositionModelDto>
 */
class ReturnPositionModelDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<ReturnPositionModelDto>
     */
    public static function getElementClass(): string
    {
        return ReturnPositionModelDto::class;
    }


    /**
     * @phpstan-param ReturnPositionModelDto ...$values
     */
    public function __construct(...$values)
    {
        parent::__construct($values);
    }
}