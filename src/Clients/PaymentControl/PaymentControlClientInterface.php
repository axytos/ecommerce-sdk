<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

interface PaymentControlClientInterface
{
    /**
     * @param \Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderData $data
     * @param \Axytos\ECommerce\Clients\PaymentControl\PaymentControlCacheInterface $paymentControlCache
     * @return string
     */
    public function check($data, $paymentControlCache);

    /**
     * @param \Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderData $data
     * @param \Axytos\ECommerce\Clients\PaymentControl\PaymentControlCacheInterface $paymentControlCache
     * @return void
     */
    public function confirm($data, $paymentControlCache);
}
