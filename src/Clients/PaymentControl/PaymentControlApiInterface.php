<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto;

interface PaymentControlApiInterface
{
    function paymentControlCheck(PaymentControlCheckRequestDto $requestData): PaymentControlCheckResponseDto;

    function paymentControlConfirm(PaymentControlConfirmRequestDto $requestData): void;
}
