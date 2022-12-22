<?php

namespace Axytos\ECommerce\Abstractions;

interface ApiKeyProviderInterface
{
    /**
     * @return string
     */
    public function getApiKey();
}
