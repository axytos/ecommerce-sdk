<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\ErrorReporting;

use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use DateTime;
use Throwable;

class ErrorReportingClient implements ErrorReportingClientInterface
{
    private ErrorReportingApiInterface $errorReportingApi;
    private LoggerAdapterInterface $logger;

    public function __construct(ErrorReportingApiInterface $errorReportingApi, LoggerAdapterInterface $logger)
    {
        $this->errorReportingApi = $errorReportingApi;
        $this->logger = $logger;
    }

    public function reportError(Throwable $throwable): void
    {
        $errorReport = new ErrorRequestModelDto();
        $errorReport->title = $this->getTitle($throwable);
        $errorReport->description = $this->getDescription($throwable);
        $errorReport->timeStamp = new DateTime();
        $this->logger->error($errorReport->title . ": " . $errorReport->description);
        $this->errorReportingApi->reportError($errorReport);
    }

    private function getTitle(Throwable $throwable): string
    {
        $title = $throwable->getMessage();

        if (empty($title))
        {
            return get_class($throwable);
        }

        return $title;
    }

    private function getDescription(Throwable $throwable): string
    {
        $description = $throwable->getTraceAsString();

        if (empty($description))
        {
            return "No Description Available";
        }

        return $description;
    }
}
