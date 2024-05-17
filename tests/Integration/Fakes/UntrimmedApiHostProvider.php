<?php

namespace Axytos\ECommerce\Tests\Integration\Fakes;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;

class UntrimmedApiHostProvider implements ApiHostProviderInterface
{
    /**
     * @var \Axytos\ECommerce\Abstractions\ApiHostProviderInterface
     */
    private $apiHostProvider;

    /**
     * @param \Axytos\ECommerce\Abstractions\ApiHostProviderInterface $apiHostProvider
     */
    public function __construct($apiHostProvider)
    {
        $this->apiHostProvider = $apiHostProvider;
    }

    /**
     * @return string
     */
    public function getApiHost()
    {
        return "    " . $this->apiHostProvider->getApiHost() . "    ";
    }
}
