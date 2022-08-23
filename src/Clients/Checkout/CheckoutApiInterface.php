<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Checkout;

interface CheckoutApiInterface
{
    function getCreditCheckAgreementText(): string;
}