<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Checkout;

use Axytos\ECommerce\Clients\Checkout\CheckoutApiInterface;
use Axytos\FinancialServices\OpenAPI\Client\Api\StaticContentApi;
use Axytos\FinancialServices\GuzzleHttp\ClientInterface;

class CheckoutApiAdapter implements CheckoutApiInterface
{
    private ClientInterface $httpClient;
    private StaticContentApi $staticContentApi;

    public function __construct(
        ClientInterface $httpClient,
        StaticContentApi $staticContentApi
    ) {
        $this->httpClient = $httpClient;
        $this->staticContentApi = $staticContentApi;
    }

    public function getCreditCheckAgreementText(): string
    {
        /**
         * At least on Windows, some this requires to configure curl.cainfo in the php.ini
         * Otherwise curl fails with CURLE_PEER_FAILED_VERIFICATION (60)
         * see:
         * - https://stackoverflow.com/a/34883260
         * - https://curl.se/docs/caextract.html
         * - https://curl.se/libcurl/c/libcurl-errors.html
         *
         * This might be related to some system configuration issues.
         * The failure happened executing within phpunit against the sandbox api
         */
        $request = $this->staticContentApi->apiV1StaticContentCreditcheckagreementGetRequest();
        $response = $this->httpClient->send($request);

        return $response->getBody()->getContents();
    }
}
