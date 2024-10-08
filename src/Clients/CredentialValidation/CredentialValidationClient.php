<?php

namespace Axytos\ECommerce\Clients\CredentialValidation;

class CredentialValidationClient implements CredentialValidationClientInterface
{
    /**
     * @var CredentialValidationApiInterface
     */
    private $CredentialValidationApi;

    public function __construct(
        CredentialValidationApiInterface $CredentialValidationApi
    ) {
        $this->CredentialValidationApi = $CredentialValidationApi;
    }

    /**
     * @return bool
     */
    public function validateApiKey()
    {
        return $this->CredentialValidationApi->getCredentialsValidation();
    }
}
