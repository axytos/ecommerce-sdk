<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Integration\Providers;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;

class ApiHostProvider implements ApiHostProviderInterface
{
    public function getApiHost(): string
    {
        return strval(file_get_contents(__DIR__ . '/../config/apiHost'));
    }
}
