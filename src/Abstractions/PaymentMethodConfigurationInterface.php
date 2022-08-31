<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Abstractions;

interface PaymentMethodConfigurationInterface
{
    public function isIgnored(string $paymentMethodId): bool;
    public function isSafe(string $paymentMethodId): bool;
    public function isUnsafe(string $paymentMethodId): bool;
    public function isNotConfigured(string $paymentMethodId): bool;
}
