<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class PaymentControlCheckResponseDto implements DtoInterface
{
    public array $approvedPaymentTypeSecurities = [];
    public ?string $processId = null;
    public ?string $decision = null;
    public ?string $step = null;
    public TransactionMetadataDto $transactionMetadata;
}
