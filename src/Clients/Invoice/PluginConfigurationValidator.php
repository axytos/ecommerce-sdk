<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Invoice;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;
use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;

class PluginConfigurationValidator
{
    private ApiHostProviderInterface $apiHostProvider;
    private ApiKeyProviderInterface $apiKeyProvider;

    public function __construct(
        ApiHostProviderInterface $apiHostProvider,
        ApiKeyProviderInterface $apiKeyProvider
    ) {
        $this->apiHostProvider = $apiHostProvider;
        $this->apiKeyProvider = $apiKeyProvider;
    }

    public function isInvalid(): bool
    {
        try {
            return $this->apiHostIsNotConfigured()
                || $this->apiKeyIsNotConfigured();
        } catch (\Throwable $th) {
            return true;
        }
    }

    private function apiHostIsNotConfigured(): bool
    {
        return empty($this->apiHostProvider->getApiHost());
    }

    private function apiKeyIsNotConfigured(): bool
    {
        return empty($this->apiKeyProvider->getApiKey());
    }
}
