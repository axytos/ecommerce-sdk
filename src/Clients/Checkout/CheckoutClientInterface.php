<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Checkout;

interface CheckoutClientInterface
{
    function mustShowCreditCheckAgreement(string $selectedPaymentMethodId): bool;

    function getCreditCheckAgreementInfo(): string;
}