<?php

namespace Axytos\ECommerce\Clients\ErrorReporting;

use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use DateTime;
use Throwable;

class ErrorReportingClient implements ErrorReportingClientInterface
{
    /**
     * @var \Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiInterface
     */
    private $errorReportingApi;
    /**
     * @var \Axytos\ECommerce\Logging\LoggerAdapterInterface
     */
    private $logger;

    public function __construct(ErrorReportingApiInterface $errorReportingApi, LoggerAdapterInterface $logger)
    {
        $this->errorReportingApi = $errorReportingApi;
        $this->logger = $logger;
    }

    /**
     * @param \Throwable $throwable
     * @return void
     */
    public function reportError($throwable)
    {
        $errorReport = new ErrorRequestModelDto();
        $errorReport->title = $this->getTitle($throwable);
        $errorReport->description = $this->getDescription($throwable);
        $errorReport->timeStamp = new DateTime();
        $this->logger->error($errorReport->title . ": " . $errorReport->description);
        $this->errorReportingApi->reportError($errorReport);
    }

    /**
     * @return string
     * @param \Throwable $throwable
     */
    private function getTitle($throwable)
    {
        $title = $throwable->getMessage();

        if (empty($title)) {
            return get_class($throwable);
        }

        return $title;
    }

    /**
     * @return string
     * @param \Throwable $throwable
     */
    private function getDescription($throwable)
    {
        $description = $throwable->getTraceAsString();

        if (empty($description)) {
            return "No Description Available";
        }

        return $description;
    }
}
