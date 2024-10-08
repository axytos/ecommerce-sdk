<?php

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;

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
     * @var ReturnPositionModelDtoCollection|null
     */
    public $positions;
}
