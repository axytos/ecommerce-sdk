<?php

namespace Axytos\ECommerce\Abstractions;

interface ApiHostProviderInterface
{
    const LIVE = 'APIHOST_LIVE';
    const SANDBOX = 'APIHOST_SANDOX';

    /**
     * @return string
     *
     * @phpstan-return self::LIVE|self::SANDBOX
     */
    public function getApiHost();
}
