<?php declare(strict_types=1);

namespace Axytos\ECommerce\Abstractions;

interface ApiKeyProviderInterface
{
    function getApiKey(): string;
}