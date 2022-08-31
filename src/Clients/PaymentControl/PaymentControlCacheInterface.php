<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;

interface PaymentControlCacheInterface
{
    public function getCheckResponse(): ?PaymentControlCheckResponseDto;
    public function setCheckResponse(PaymentControlCheckResponseDto $checkResponse): void;
    public function getCheckRequestHash(): ?string;
    public function setCheckRequestHash(string $hash): void;
}
