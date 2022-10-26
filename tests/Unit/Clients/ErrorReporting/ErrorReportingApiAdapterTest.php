<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\Clients\PaymentControl;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiAdapter;
use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\FinancialServices\OpenAPI\Client\Api\ErrorApi;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsErrorRequestModel;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ErrorReportingApiAdapterTest extends TestCase
{
    /** @var ErrorApi&MockObject */
    private ErrorApi $errorApi;

    /** @var DtoOpenApiModelMapper&MockObject */
    private DtoOpenApiModelMapper $mapper;

    private ErrorReportingApiAdapter $sut;

    public function setUp(): void
    {
        $this->errorApi = $this->createMock(ErrorApi::class);
        $this->mapper = $this->createMock(DtoOpenApiModelMapper::class);

        $this->sut = new ErrorReportingApiAdapter(
            $this->errorApi,
            $this->mapper
        );
    }

    public function test_reportError(): void
    {
        $errorReport = $this->createMock(ErrorRequestModelDto::class);
        $errorRequest = $this->createMock(AxytosApiModelsErrorRequestModel::class);

        $this->mapper
            ->method('toOpenApiModel')
            ->with($errorReport, AxytosApiModelsErrorRequestModel::class)
            ->willReturn($errorRequest);

        $this->errorApi
            ->expects($this->once())
            ->method('apiV1ErrorReportPost')
            ->with($errorRequest);

        $this->sut->reportError($errorReport);
    }

    public function test_reportError_does_not_propagate_errors(): void
    {
        $throwable = null;

        $errorReport = $this->createMock(ErrorRequestModelDto::class);
        $errorRequest = $this->createMock(AxytosApiModelsErrorRequestModel::class);

        $this->mapper
            ->method('toOpenApiModel')
            ->with($errorReport, AxytosApiModelsErrorRequestModel::class)
            ->willReturn($errorRequest);

        $this->errorApi
            ->expects($this->once())
            ->method('apiV1ErrorReportPost')
            ->with($errorRequest)
            ->willThrowException(new ApiException());

        try {
            $this->sut->reportError($errorReport);
        } catch (\Throwable $th) {
            $throwable = $th;
        }

        $this->assertNull($throwable);
    }
}
