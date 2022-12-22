<?php

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;

class ApiHostProvider implements ApiHostProviderInterface
{
    /**
     * @return string
     */
    public function getApiHost()
    {
        return strval(file_get_contents(__DIR__ . '/../config/apiHost'));
    }
}
