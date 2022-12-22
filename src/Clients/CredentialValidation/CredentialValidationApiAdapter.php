<?php

namespace Axytos\ECommerce\Clients\CredentialValidation;

use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiInterface;
use Axytos\FinancialServices\OpenAPI\Client\Api\CredentialsApi;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;

class CredentialValidationApiAdapter implements CredentialValidationApiInterface
{
    /**
     * @var \Axytos\FinancialServices\OpenAPI\Client\Api\CredentialsApi
     */
    private $credentialsApi;

    public function __construct(CredentialsApi $credentialsApi)
    {
        $this->credentialsApi = $credentialsApi;
    }

    /**
     * @return bool
     */
    public function getCredentialsValidation()
    {
        try {
            $this->credentialsApi->apiV1CredentialsValidateGetWithHttpInfo();
            return true;
        } catch (ApiException $e) {
            return false;
        }
    }
}
