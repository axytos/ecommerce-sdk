<?php

namespace Axytos\ECommerce\Clients\Checkout;

use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;

class CheckoutClient implements CheckoutClientInterface
{
    /**
     * @var PaymentMethodConfigurationInterface
     */
    private $paymentMethodConfiguration;
    /**
     * @var CheckoutApiInterface
     */
    private $checkoutApi;

    public function __construct(
        PaymentMethodConfigurationInterface $paymentMethodConfiguration,
        CheckoutApiInterface $checkoutApi
    ) {
        $this->paymentMethodConfiguration = $paymentMethodConfiguration;
        $this->checkoutApi = $checkoutApi;
    }

    /**
     * @param string $selectedPaymentMethodId
     *
     * @return bool
     */
    public function mustShowCreditCheckAgreement($selectedPaymentMethodId)
    {
        return $this->paymentMethodConfiguration->isSafe($selectedPaymentMethodId)
            || $this->paymentMethodConfiguration->isUnsafe($selectedPaymentMethodId);
    }

    /**
     * @return string
     */
    public function getCreditCheckAgreementInfo()
    {
        try {
            return $this->checkoutApi->getCreditCheckAgreementText();
        } catch (\Throwable $th) {
            throw new CreditCheckAgreementLoadFailedException($th);
        } catch (\Exception $th) { // @phpstan-ignore-line / php5 compatibility
            throw new CreditCheckAgreementLoadFailedException($th);
        }
    }
}
