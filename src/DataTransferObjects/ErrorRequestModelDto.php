<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

use Axytos\ECommerce\DataMapping\DtoInterface;
use DateTimeInterface;

class ErrorRequestModelDto implements DtoInterface
{
    public string $title;
    public string $description;
    public DateTimeInterface $timeStamp;
}