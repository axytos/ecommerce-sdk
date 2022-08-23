<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\ErrorReporting;

use Throwable;

interface ErrorReportingClientInterface
{
    function reportError(Throwable $throwable): void;
}