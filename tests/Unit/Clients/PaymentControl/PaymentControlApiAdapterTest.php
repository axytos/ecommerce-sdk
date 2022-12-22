<?php

namespace Axytos\ECommerce\Tests\Unit\Clients\PaymentControl;

use Axytos\ECommerce\Clients\PaymentControl\PaymentControlApiAdapter;
use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto;
use Axytos\FinancialServices\OpenAPI\Client\Api\CheckApi;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlConfirmRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlResponse;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PaymentControlApiAdapterTest extends TestCase
{
    /** @var CheckApi&MockObject */
    private $checkApi;

    /** @var DtoOpenApiModelMapper&MockObject */
    private $mapper;

    /**
     * @var \Axytos\ECommerce\Clients\PaymentControl\PaymentControlApiAdapter
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->checkApi = $this->createMock(CheckApi::class);
        $this->mapper = $this->createMock(DtoOpenApiModelMapper::class);

        $this->sut = new PaymentControlApiAdapter(
            $this->checkApi,
            $this->mapper
        );
    }

    /**
     * @return void
     */
    public function test_postPaymentControlCheck_returns_mapped_result_from_response()
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

    /**
     * @return void
     */
    public function test_postPaymentControlConfirm_sends_payment_control_confirm_request()
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
