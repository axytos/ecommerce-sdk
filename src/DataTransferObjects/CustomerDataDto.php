<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;
use DateTimeInterface;

class CustomerDataDto implements DtoInterface
{
    public ?string $externalCustomerId = null;

    public ?DateTimeInterface $dateOfBirth = null;

    public ?string $email = null;

    public ?CompanyDto $company = null;
}
