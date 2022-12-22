<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;
use DateTimeInterface;

class CustomerDataDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $externalCustomerId;

    /**
     * @var \DateTimeInterface|null
     */
    public $dateOfBirth;

    /**
     * @var string|null
     */
    public $email;

    /**
     * @var \Axytos\ECommerce\DataTransferObjects\CompanyDto|null
     */
    public $company;
}
