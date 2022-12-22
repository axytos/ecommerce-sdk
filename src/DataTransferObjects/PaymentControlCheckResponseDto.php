<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentControlCheckResponseDto implements DtoInterface
{
    /**
     * @var mixed[]
     */
    public $approvedPaymentTypeSecurities = [];
    /**
     * @var string|null
     */
    public $processId;
    /**
     * @var string|null
     */
    public $decision;
    /**
     * @var string|null
     */
    public $step;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\TransactionMetadataDto
     */
    public $transactionMetadata;
}
