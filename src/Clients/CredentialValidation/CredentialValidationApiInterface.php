<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\CredentialValidation;

interface CredentialValidationApiInterface
{
    public function getCredentialsValidation(): bool;
}
