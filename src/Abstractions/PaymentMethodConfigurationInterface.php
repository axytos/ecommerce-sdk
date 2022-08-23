<?php declare(strict_types=1);

namespace Axytos\ECommerce\Abstractions;

interface PaymentMethodConfigurationInterface
{
    function isIgnored(string $paymentMethodId): bool;
    function isSafe(string $paymentMethodId): bool;
    function isUnsafe(string $paymentMethodId): bool;
    function isNotConfigured(string $paymentMethodId): bool;
}