<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Checkout;

interface CheckoutApiInterface
{
    public function getCreditCheckAgreementText(): string;
}
