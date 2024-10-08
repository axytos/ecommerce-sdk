<?php

namespace Axytos\ECommerce\Tests\Unit\Clients\ErrorReporting;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClient;
use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ErrorReportingClientTest extends TestCase
{
    /** @var ErrorReportingApiInterface&MockObject */
    private $errorReportingApi;

    /** @var LoggerAdapterInterface&MockObject */
    private $logger;

    /**
     * @var ErrorReportingClient
     */
    private $sut;

    /**
     * @return void
     *
     * @before
     */
    #[Before]
    public function beforeEach()
    {
        $this->errorReportingApi = $this->createMock(ErrorReportingApiInterface::class);
        $this->logger = $this->createMock(LoggerAdapterInterface::class);

        $this->sut = new ErrorReportingClient(
            $this->errorReportingApi,
            $this->logger
        );
    }

    /**
     * @return void
     */
    public function test_report_error_uses_message_as_title()
    {
        $exception = new \Exception('message');

        $matcher = $this->callback(function (ErrorRequestModelDto $errorReport) {
            return 'message' === $errorReport->title;
        });

        $this->errorReportingApi
            ->expects($this->once())
            ->method('reportError')
            ->with($matcher)
        ;

        $this->sut->reportError($exception);
    }

    /**
     * @return void
     */
    public function test_report_error_uses_class_name_as_title_if_message_is_empty()
    {
        $exception = new \Exception();

        $matcher = $this->callback(function (ErrorRequestModelDto $errorReport) {
            return \Exception::class === $errorReport->title;
        });

        $this->errorReportingApi
            ->expects($this->once())
            ->method('reportError')
            ->with($matcher)
        ;

        $this->sut->reportError($exception);
    }

    /**
     * @return void
     */
    public function test_report_error_sets_description()
    {
        $exception = new \Exception();

        $matcher = $this->callback(function (ErrorRequestModelDto $errorReport) {
            return '' !== $errorReport->description;
        });

        $this->errorReportingApi
            ->expects($this->once())
            ->method('reportError')
            ->with($matcher)
        ;

        $this->sut->reportError($exception);
    }

    /**
     * @return void
     */
    public function test_report_error_sets_time_stamp()
    {
        $exception = new \Exception();

        $matcher = $this->callback(function (ErrorRequestModelDto $errorReport) {
            return $errorReport->timeStamp instanceof \DateTime;
        });

        $this->errorReportingApi
            ->expects($this->once())
            ->method('reportError')
            ->with($matcher)
        ;

        $this->sut->reportError($exception);
    }
}
