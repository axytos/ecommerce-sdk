<?php

namespace Axytos\ECommerce\Clients\ErrorReporting;

use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;

class ErrorReportingClient implements ErrorReportingClientInterface
{
    /**
     * @var ErrorReportingApiInterface
     */
    private $errorReportingApi;
    /**
     * @var LoggerAdapterInterface
     */
    private $logger;

    public function __construct(ErrorReportingApiInterface $errorReportingApi, LoggerAdapterInterface $logger)
    {
        $this->errorReportingApi = $errorReportingApi;
        $this->logger = $logger;
    }

    /**
     * @param \Throwable $throwable
     *
     * @return void
     */
    public function reportError($throwable)
    {
        $errorReport = new ErrorRequestModelDto();
        $errorReport->title = $this->getTitle($throwable);
        $errorReport->description = $this->getDescription($throwable);
        $errorReport->timeStamp = new \DateTime();
        $this->logger->error($errorReport->title . ': ' . $errorReport->description);
        $this->errorReportingApi->reportError($errorReport);
    }

    /**
     * @param \Throwable $throwable
     *
     * @return string
     */
    private function getTitle($throwable)
    {
        $title = $throwable->getMessage();

        if ('' === $title) {
            return get_class($throwable);
        }

        return $title;
    }

    /**
     * @param \Throwable $throwable
     *
     * @return string
     */
    private function getDescription($throwable)
    {
        $description = $throwable->getTraceAsString();

        if ('' === $description) {
            return 'No Description Available';
        }

        return $description;
    }
}
