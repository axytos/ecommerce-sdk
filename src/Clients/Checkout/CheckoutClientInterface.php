<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Checkout;

interface CheckoutClientInterface
{
    public function mustShowCreditCheckAgreement(string $selectedPaymentMethodId): bool;

    public function getCreditCheckAgreementInfo(): string;
}
