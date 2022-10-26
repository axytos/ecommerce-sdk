<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;

class PaymentMethodConfiguration implements PaymentMethodConfigurationInterface
{
    public function isIgnored(string $paymentMethodId): bool
    {
        return false;
    }

    public function isSafe(string $paymentMethodId): bool
    {
        return false;
    }

    public function isUnsafe(string $paymentMethodId): bool
    {
        return false;
    }

    public function isNotConfigured(string $paymentMethodId): bool
    {
        return false;
    }
}
