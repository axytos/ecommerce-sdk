<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class BasketDto implements DtoInterface
{
    /**
     * @var float|null
     */
    public $netTotal;
    /**
     * @var float|null
     */
    public $grossTotal;
    /**
     * @var string|null
     */
    public $currency;
    /**
     * @var BasketPositionDtoCollection
     */
    public $positions;
}
