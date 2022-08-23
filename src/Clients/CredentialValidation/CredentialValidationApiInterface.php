<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\CredentialValidation;

interface CredentialValidationApiInterface
{
    function getCredentialsValidation(): bool;
}