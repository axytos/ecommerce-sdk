<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class TransactionMetadataDto implements DtoInterface
{
    /**
     * @var string
     */
    public $transactionId;
    /**
     * @var string
     */
    public $transactionInfoSignature;
    /**
     * @var \DateTimeImmutable
     */
    public $transactionTimestamp;
    /**
     * @var \DateTimeImmutable
     */
    public $transactionExpirationTimestamp;
}
