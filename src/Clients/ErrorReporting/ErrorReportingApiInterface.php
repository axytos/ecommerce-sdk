<?php

namespace Axytos\ECommerce\Clients\ErrorReporting;

use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;

interface ErrorReportingApiInterface
{
    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto $errorRequestModelDto
     * @return void
     */
    public function reportError($errorRequestModelDto);
}
