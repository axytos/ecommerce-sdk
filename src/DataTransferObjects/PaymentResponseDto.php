<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentResponseDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $id;

    /**
     * @var \DateTimeImmutable|null
     */
    public $date;

    /**
     * @var string|null
     */
    public $invoiceNumber;

    /**
     * @var string|null
     */
    public $externalOrderId;

    /**
     * @var float|null
     */
    public $amount;

    /**
     * @var string|null
     */
    public $currency;
}
