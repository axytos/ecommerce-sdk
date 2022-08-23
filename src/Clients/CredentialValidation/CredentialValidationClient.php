<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\CredentialValidation;

class CredentialValidationClient implements CredentialValidationClientInterface
{
    private CredentialValidationApiInterface $CredentialValidationApi;

    public function __construct(
        CredentialValidationApiInterface $CredentialValidationApi
    ) {
        $this->CredentialValidationApi = $CredentialValidationApi;
    }

    public function validateApiKey(): bool
    {
        return $this->CredentialValidationApi->getCredentialsValidation();
    }
}
