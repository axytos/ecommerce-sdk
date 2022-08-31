<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Abstractions;

abstract class FallbackModes
{
    const ALL_PAYMENT_METHODS = 'ALL_PAYMENT_METHODS';
    const NO_UNSAFE_PAYMENT_METHODS = 'NO_UNSAFE_PAYMENT_METHODS';
    const IGNORED_AND_NOT_CONFIGURED_PAYMENT_METHODS = 'IGNORED_AND_NOT_CONFIGURED_PAYMENT_METHODS';
}
