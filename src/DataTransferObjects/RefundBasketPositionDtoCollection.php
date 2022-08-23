<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoCollection;

/**
 * @phpstan-extends DtoCollection<RefundBasketPositionDto>
 */
class RefundBasketPositionDtoCollection extends DtoCollection
{
    /**
     * @phpstan-return class-string<RefundBasketPositionDto>
     */
    public static function getElementClass(): string
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