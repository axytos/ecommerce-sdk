<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;

interface PaymentControlCacheInterface
{
    /**
     * @return \Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto|null
     */
    public function getCheckResponse();
    /**
     * @param \Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto $checkResponse
     * @return void
     */
    public function setCheckResponse($checkResponse);
    /**
     * @return string|null
     */
    public function getCheckRequestHash();
    /**
     * @param string $hash
     * @return void
     */
    public function setCheckRequestHash($hash);
}
