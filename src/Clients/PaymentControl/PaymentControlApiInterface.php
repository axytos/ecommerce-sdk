<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto;

interface PaymentControlApiInterface
{
    /**
     * @param \Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto $requestData
     * @return \Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto
     */
    public function paymentControlCheck($requestData);

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto $requestData
     * @return void
     */
    public function paymentControlConfirm($requestData);
}
