<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;
use DateTimeInterface;

class ReturnRequestModelDto implements DtoInterface
{
    /**
     * @var string
     */
    public $externalOrderId;
    /**
     * @var string|null
     */
    public $externalSubOrderId;
    /**
     * @var \DateTimeInterface|null
     */
    public $returnDate;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection|null
     */
    public $positions;
}
