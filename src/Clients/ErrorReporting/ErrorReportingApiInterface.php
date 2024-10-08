<?php

namespace Axytos\ECommerce\Clients\ErrorReporting;

interface ErrorReportingApiInterface
{
    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto $errorRequestModelDto
     *
     * @return void
     */
    public function reportError($errorRequestModelDto);
}
