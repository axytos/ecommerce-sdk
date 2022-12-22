<?php

namespace Axytos\ECommerce\Clients\CredentialValidation;

interface CredentialValidationClientInterface
{
    /**
     * @return bool
     */
    public function validateApiKey();
}
