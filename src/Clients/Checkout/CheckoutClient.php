<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Checkout;

use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;

class CheckoutClient implements CheckoutClientInterface
{
    private PaymentMethodConfigurationInterface $paymentMethodConfiguration;
    private CheckoutApiInterface $checkoutApi;

    public function __construct(
        PaymentMethodConfigurationInterface $paymentMethodConfiguration,
        CheckoutApiInterface $checkoutApi
    ) {
        $this->paymentMethodConfiguration = $paymentMethodConfiguration;
        $this->checkoutApi = $checkoutApi;
    }

    public function mustShowCreditCheckAgreement(string $selectedPaymentMethodId): bool
    {
        return $this->paymentMethodConfiguration->isSafe($selectedPaymentMethodId)
            || $this->paymentMethodConfiguration->isUnsafe($selectedPaymentMethodId);
    }

    public function getCreditCheckAgreementInfo(): string
    {
        try {
            return $this->checkoutApi->getCreditCheckAgreementText();
        } catch (\Throwable $th) {
            throw new CreditCheckAgreementLoadFailedException($th);
        }
    }
}
