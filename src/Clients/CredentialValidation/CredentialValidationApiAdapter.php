<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\CredentialValidation;

use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiInterface;
use Axytos\FinancialServices\OpenAPI\Client\Api\CredentialsApi;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;

class CredentialValidationApiAdapter implements CredentialValidationApiInterface
{
    private CredentialsApi $credentialsApi;

    public function __construct(CredentialsApi $credentialsApi)
    {
        $this->credentialsApi = $credentialsApi;
    }

    public function getCredentialsValidation(): bool
    {
        try {
            $this->credentialsApi->apiV1CredentialsValidateGetWithHttpInfo();
            return true;
        } catch (ApiException $e) {
            return false;
        }
    }
}
