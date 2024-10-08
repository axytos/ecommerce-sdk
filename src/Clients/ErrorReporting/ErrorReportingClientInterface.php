<?php

namespace Axytos\ECommerce\Clients\ErrorReporting;

interface ErrorReportingClientInterface
{
    /**
     * @param \Throwable $throwable
     *
     * @return void
     */
    public function reportError($throwable);
}
