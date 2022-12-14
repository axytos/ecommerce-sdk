<?php

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\FallbackModeConfigurationInterface;
use Axytos\ECommerce\Abstractions\FallbackModes;

class FallbackModeConfiguration implements FallbackModeConfigurationInterface
{
    /**
     * @return string
     */
    public function getFallbackMode()
    {
        return FallbackModes::ALL_PAYMENT_METHODS;
    }
}
