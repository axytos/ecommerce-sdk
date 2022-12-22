<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentControlBasketDto implements DtoInterface
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
     * @var \Axytos\ECommerce\DataTransferObjects\PaymentControlBasketPositionDtoCollection
     */
    public $positions;
}
