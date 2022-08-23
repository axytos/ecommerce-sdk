<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;
use DateTimeImmutable;

class TransactionMetadataDto implements DtoInterface
{
    public string $transactionId;
    public string $transactionInfoSignature;
    public DateTimeImmutable $transactionTimestamp;
    public DateTimeImmutable $transactionExpirationTimestamp;
}