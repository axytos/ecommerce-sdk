<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Clients\PaymentControl;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClient;
use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Throwable;

class ErrorReportingClientTest extends TestCase
{
    /** @var ErrorReportingApiInterface&MockObject */
    private ErrorReportingApiInterface $errorReportingApi;

    /** @var LoggerAdapterInterface&MockObject */
    private LoggerAdapterInterface $logger;

    private ErrorReportingClient $sut;

    public function setUp(): void
    {
        $this->errorReportingApi = $this->createMock(ErrorReportingApiInterface::class);
        $this->logger = $this->createMock(LoggerAdapterInterface::class);

        $this->sut = new ErrorReportingClient(
            $this->errorReportingApi,
            $this->logger
        );
    }

    public function test_reportError_uses_message_as_title(): void
    {
        $exception = new Exception('message');

        $matcher = $this->callback(function(ErrorRequestModelDto $errorReport){
            return $errorReport->title === 'message';
        });

        $this->errorReportingApi
            ->expects($this->once())
            ->method('reportError')
            ->with($matcher);

        $this->sut->reportError($exception);
    }

    public function test_reportError_uses_class_name_as_title_if_message_is_empty(): void
    {
        $exception = new Exception();

        $matcher = $this->callback(function(ErrorRequestModelDto $errorReport){
            return $errorReport->title === Exception::class;
        });

        $this->errorReportingApi
            ->expects($this->once())
            ->method('reportError')
            ->with($matcher);

        $this->sut->reportError($exception);
    }

    public function test_reportError_sets_description(): void
    {
        $exception = new Exception();

        $matcher = $this->callback(function(ErrorRequestModelDto $errorReport){
            return !empty($errorReport->description);
        });

        $this->errorReportingApi
            ->expects($this->once())
            ->method('reportError')
            ->with($matcher);

        $this->sut->reportError($exception);
    }

    public function test_reportError_sets_timeStamp(): void
    {
        $exception = new Exception();

        $matcher = $this->callback(function(ErrorRequestModelDto $errorReport){
            return $errorReport->timeStamp instanceof DateTime;
        });

        $this->errorReportingApi
            ->expects($this->once())
            ->method('reportError')
            ->with($matcher);

        $this->sut->reportError($exception);
    }
}