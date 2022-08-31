<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto;

interface PaymentControlApiInterface
{
    public function paymentControlCheck(PaymentControlCheckRequestDto $requestData): PaymentControlCheckResponseDto;

    public function paymentControlConfirm(PaymentControlConfirmRequestDto $requestData): void;
}
