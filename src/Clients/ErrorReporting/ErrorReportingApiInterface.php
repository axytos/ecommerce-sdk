<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\ErrorReporting;

use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;

interface ErrorReportingApiInterface
{
    function reportError(ErrorRequestModelDto $errorRequestModelDto): void;
}