<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class OrderPreCheckResponseDto implements DtoInterface
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
     * @var TransactionMetadataDto|null
     */
    public $transactionMetadata;
    /**
     * @var string|null
     */
    public $riskTaker;
}
