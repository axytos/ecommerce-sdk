<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\FallbackModeConfigurationInterface;
use Axytos\ECommerce\Abstractions\FallbackModes;

class FallbackModeConfiguration implements FallbackModeConfigurationInterface
{
    public function getFallbackMode(): string
    {
        return FallbackModes::ALL_PAYMENT_METHODS;
    }
}
