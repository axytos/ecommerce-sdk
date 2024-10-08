<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

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
     * @var CompanyDto|null
     */
    public $company;
}
