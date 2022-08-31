<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class InvoiceAddressDto implements DtoInterface
{
    public ?string $company = null;

    public ?string $salutation = null;

    public ?string $firstname = null;

    public ?string $lastname = null;

    public ?string $zipCode = null;

    public ?string $city = null;

    public ?string $region = null;

    public ?string $country = null;

    public ?string $vatId = null;

    public ?string $addressLine1 = null;

    public ?string $addressLine2 = null;

    public ?string $addressLine3 = null;

    public ?string $addressLine4 = null;
}
