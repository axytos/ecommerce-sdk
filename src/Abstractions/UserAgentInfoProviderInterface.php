<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Abstractions;

interface UserAgentInfoProviderInterface
{
    public function getPluginName(): string;
    public function getPluginVersion(): string;
    public function getShopSystemName(): string;
    public function getShopSystemVersion(): string;
}
