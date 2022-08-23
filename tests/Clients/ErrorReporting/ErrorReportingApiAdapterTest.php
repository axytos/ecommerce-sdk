<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Clients\PaymentControl;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiAdapter;
use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\FinancialServicesAPI\Client\Api\ErrorApi;
use Axytos\FinancialServicesAPI\Client\Model\AxytosApiModelsErrorRequestModel;
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
}