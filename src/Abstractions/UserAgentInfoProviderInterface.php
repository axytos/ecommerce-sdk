<?php declare(strict_types=1);

namespace Axytos\ECommerce\Abstractions;

interface UserAgentInfoProviderInterface
{
    function getPluginName(): string;
    function getPluginVersion(): string;
    function getShopSystemName(): string;
    function getShopSystemVersion(): string;
}