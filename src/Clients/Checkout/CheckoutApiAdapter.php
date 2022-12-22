<?php

namespace Axytos\ECommerce\Clients\Checkout;

use Axytos\ECommerce\Clients\Checkout\CheckoutApiInterface;
use Axytos\FinancialServices\GuzzleHttp\ClientInterface;

class CheckoutApiAdapter implements CheckoutApiInterface
{
    /**
     * @var \Axytos\FinancialServices\GuzzleHttp\ClientInterface
     */
    private $httpClient;
    /**
     * @var StaticContentApiProxy
     */
    private $staticContentApi;

    public function __construct(
        ClientInterface $httpClient,
        StaticContentApiProxy $staticContentApi
    ) {
        $this->httpClient = $httpClient;
        $this->staticContentApi = $staticContentApi;
    }

    /**
     * @return string
     */
    public function getCreditCheckAgreementText()
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
