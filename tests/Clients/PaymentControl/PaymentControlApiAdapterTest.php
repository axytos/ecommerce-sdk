<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Clients\PaymentControl;

use Axytos\ECommerce\Clients\PaymentControl\PaymentControlApiAdapter;
use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto;
use Axytos\FinancialServicesAPI\Client\Api\CheckApi;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlConfirmRequest;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlRequest;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlResponse;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PaymentControlApiAdapterTest extends TestCase
{
    /** @var CheckApi&MockObject */
    private CheckApi $checkApi;
    
    /** @var DtoOpenApiModelMapper&MockObject */
    private DtoOpenApiModelMapper $mapper;

    private PaymentControlApiAdapter $sut;

    public function setUp(): void
    {
        $this->checkApi = $this->createMock(CheckApi::class);
        $this->mapper = $this->createMock(DtoOpenApiModelMapper::class);

        $this->sut = new PaymentControlApiAdapter(
            $this->checkApi,
            $this->mapper
        );
    }

    public function test_postPaymentControlCheck_returns_mapped_result_from_response(): void
    {
        $requestData = $this->createMock(PaymentControlCheckRequestDto::class);
        $result = $this->createMock(PaymentControlCheckResponseDto::class);
        $request = $this->createMock(AxytosCommonPublicAPIModelsPaymentControlPaymentControlRequest::class);
        $response = $this->createMock(AxytosCommonPublicAPIModelsPaymentControlPaymentControlResponse::class);

        $this->mapper
            ->method("toOpenApiModel")
            ->with($requestData, AxytosCommonPublicAPIModelsPaymentControlPaymentControlRequest::class)
            ->willReturn($request);

        $this->mapper
            ->method("toDataTransferObject")
            ->with($response, PaymentControlCheckResponseDto::class)
            ->willReturn($result);

        $this->checkApi
            ->method("apiV1CheckRiskPaymentcontrolCheckPost")
            ->with($request)
            ->willReturn($response);

        $actual = $this->sut->paymentControlCheck($requestData);

        $this->assertSame($result, $actual);
    }

    public function test_postPaymentControlConfirm_sends_payment_control_confirm_request(): void
    {
        $requestData = $this->createMock(PaymentControlConfirmRequestDto::class);
        $request = $this->createMock(AxytosCommonPublicAPIModelsPaymentControlPaymentControlConfirmRequest::class);

        $this->mapper
            ->method("toOpenApiModel")
            ->with($requestData, AxytosCommonPublicAPIModelsPaymentControlPaymentControlConfirmRequest::class)
            ->willReturn($request);

        $this->checkApi
            ->expects($this->once())
            ->method("apiV1CheckRiskPaymentcontrolConfirmPost")
            ->with($request);

        $this->sut->paymentControlConfirm($requestData);
    }
}
