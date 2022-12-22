<?php

namespace Axytos\ECommerce\Clients\Checkout;

interface CheckoutClientInterface
{
    /**
     * @param string $selectedPaymentMethodId
     * @return bool
     */
    public function mustShowCreditCheckAgreement($selectedPaymentMethodId);

    /**
     * @return string
     */
    public function getCreditCheckAgreementInfo();
}
