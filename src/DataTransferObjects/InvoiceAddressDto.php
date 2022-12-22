<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class InvoiceAddressDto implements DtoInterface
{
    /**
     * @var string|null
     */
    public $company;

    /**
     * @var string|null
     */
    public $salutation;

    /**
     * @var string|null
     */
    public $firstname;

    /**
     * @var string|null
     */
    public $lastname;

    /**
     * @var string|null
     */
    public $zipCode;

    /**
     * @var string|null
     */
    public $city;

    /**
     * @var string|null
     */
    public $region;

    /**
     * @var string|null
     */
    public $country;

    /**
     * @var string|null
     */
    public $vatId;

    /**
     * @var string|null
     */
    public $addressLine1;

    /**
     * @var string|null
     */
    public $addressLine2;

    /**
     * @var string|null
     */
    public $addressLine3;

    /**
     * @var string|null
     */
    public $addressLine4;
}
