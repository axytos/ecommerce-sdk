<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\CredentialValidation;

interface CredentialValidationClientInterface
{
    public function validateApiKey(): bool;
}
