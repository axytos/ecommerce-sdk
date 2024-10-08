<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

class ErrorRequestModelDto implements DtoInterface
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $description;
    /**
     * @var \DateTimeInterface
     */
    public $timeStamp;
}
