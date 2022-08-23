<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;

interface PaymentControlCacheInterface
{
    
    function getCheckResponse(): ?PaymentControlCheckResponseDto;
    function setCheckResponse(PaymentControlCheckResponseDto $checkResponse): void;
    function getCheckRequestHash(): ?string;
    function setCheckRequestHash(string $hash): void;
}
