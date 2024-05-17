<?php

namespace Axytos\ECommerce\Tests\Integration\Fakes;

use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;

class UntrimmedApiKeyProvider implements ApiKeyProviderInterface
{
    /**
     * @var \Axytos\ECommerce\Abstractions\ApiKeyProviderInterface
     */
    private $apiKeyProvider;

    /**
     * @param \Axytos\ECommerce\Abstractions\ApiKeyProviderInterface $apiKeyProvider
     */
    public function __construct($apiKeyProvider)
    {
        $this->apiKeyProvider = $apiKeyProvider;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return "    " . $this->apiKeyProvider->getApiKey() . "    ";
    }
}
