<?php

namespace Axytos\ECommerce\Tests\Integration\Fakes;

use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;

class InvalidApiKeyProvider implements ApiKeyProviderInterface
{
    /**
     * @return string
     */
    public function getApiKey()
    {
        return 'invalid-api-key';
    }
}
