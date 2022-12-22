<?php

namespace Axytos\ECommerce\Clients\ErrorReporting;

use Throwable;

interface ErrorReportingClientInterface
{
    /**
     * @param \Throwable $throwable
     * @return void
     */
    public function reportError($throwable);
}
