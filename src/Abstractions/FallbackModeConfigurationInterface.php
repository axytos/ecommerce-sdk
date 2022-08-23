<?php declare(strict_types=1);

namespace Axytos\ECommerce\Abstractions;

interface FallbackModeConfigurationInterface
{
    function getFallbackMode(): string;
}