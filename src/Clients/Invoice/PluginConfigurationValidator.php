<?php

namespace Axytos\ECommerce\Clients\Invoice;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;
use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;

class PluginConfigurationValidator
{
    /**
     * @var ApiHostProviderInterface
     */
    private $apiHostProvider;
    /**
     * @var ApiKeyProviderInterface
     */
    private $apiKeyProvider;

    public function __construct(
        ApiHostProviderInterface $apiHostProvider,
        ApiKeyProviderInterface $apiKeyProvider
    ) {
        $this->apiHostProvider = $apiHostProvider;
        $this->apiKeyProvider = $apiKeyProvider;
    }

    /**
     * @return bool
     */
    public function isInvalid()
    {
        try {
            return $this->apiHostIsNotConfigured()
                || $this->apiKeyIsNotConfigured();
        } catch (\Throwable $th) {
            return true;
        } catch (\Exception $th) { // @phpstan-ignore-line / php5 compatibility
            return true;
        }
    }

    /**
     * @return bool
     */
    private function apiHostIsNotConfigured()
    {
        return '' === strval($this->apiHostProvider->getApiHost());
    }

    /**
     * @return bool
     */
    private function apiKeyIsNotConfigured()
    {
        return '' === strval($this->apiKeyProvider->getApiKey());
    }
}
