<?php

namespace Axytos\ECommerce\Abstractions;

interface PaymentMethodConfigurationInterface
{
    /**
     * @param string $paymentMethodId
     *
     * @return bool
     */
    public function isIgnored($paymentMethodId);

    /**
     * @param string $paymentMethodId
     *
     * @return bool
     */
    public function isSafe($paymentMethodId);

    /**
     * @param string $paymentMethodId
     *
     * @return bool
     */
    public function isUnsafe($paymentMethodId);

    /**
     * @param string $paymentMethodId
     *
     * @return bool
     */
    public function isNotConfigured($paymentMethodId);
}
