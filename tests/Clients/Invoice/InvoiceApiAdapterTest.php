<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Clients\Invoice;

use PHPUnit\Framework\TestCase;
use Axytos\FinancialServicesAPI\Client\Api\PaymentApi;
use Axytos\FinancialServicesAPI\Client\Api\PaymentsApi;
use PHPUnit\Framework\MockObject\MockObject;
use Axytos\ECommerce\Clients\Invoice\InvoiceApiAdapter;
use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataTransferObjects\RefundRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentResponseDto;
use Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto;
use Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto;
use Axytos\FinancialServicesAPI\Client\Model\AxytosApiModelsInvoiceCreationModel;
use Axytos\FinancialServicesAPI\Client\Model\AxytosApiModelsPaymentResponseModel;
use Axytos\FinancialServicesAPI\Client\Model\AxytosApiModelsRefundRequestModel;
use Axytos\FinancialServicesAPI\Client\Model\AxytosApiModelsReturnRequestModel;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderCreateRequest;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlOrderPrecheckResponse;

class InvoiceApiAdapterTest extends TestCase
{    
    /** @var PaymentsApi&MockObject */
    private PaymentsApi $paymentsApi;

    /** @var PaymentApi&MockObject */
    private PaymentApi $paymentApi;
    
    /** @var DtoOpenApiModelMapper&MockObject */
    private DtoOpenApiModelMapper $mapper;

    private InvoiceApiAdapter $sut;

    public function setUp(): void
    {
        $this->paymentsApi = $this->createMock(PaymentsApi::class);
        $this->paymentApi = $this->createMock(PaymentApi::class);
        $this->mapper = $this->createMock(DtoOpenApiModelMapper::class);

        $this->sut = new InvoiceApiAdapter(
            $this->paymentsApi,
            $this->paymentApi,
            $this->mapper
        );
    }

    public function test_precheck_returns_response_data_transfer_object(): void
    {
        $requestDto = $this->createMock(OrderPreCheckRequestDto::class);
        $responseDto = $this->createMock(OrderPreCheckResponseDto::class);
        $requestModel = $this->createMock(AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest::class);
        $responseModel = $this->createMock(AxytosCommonPublicAPIModelsPaymentControlOrderPrecheckResponse::class);

        $this->mapper
            ->method('toOpenApiModel')
            ->with($requestDto, AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest::class)
            ->willReturn($requestModel);

        $this->mapper
            ->method('toDataTransferObject')
            ->with($responseModel, OrderPreCheckResponseDto::class)
            ->willReturn($responseDto);

        $this->paymentsApi
            ->method('apiV1PaymentsInvoiceOrderPrecheckPost')
            ->with($requestModel)
            ->willReturn($responseModel);

        $actual = $this->sut->precheck($requestDto);

        $this->assertSame($responseDto, $actual);
    }

    public function test_confirm_sends_confirm_request(): void
    {
        $requestDto = $this->createMock(OrderCreateRequestDto::class);
        $requestModel = $this->createMock(AxytosCommonPublicAPIModelsOrderOrderCreateRequest::class);

        $this->mapper
            ->method('toOpenApiModel')
            ->with($requestDto, AxytosCommonPublicAPIModelsOrderOrderCreateRequest::class)
            ->willReturn($requestModel);

        $this->paymentsApi
            ->expects($this->once())
            ->method('apiV1PaymentsInvoiceOrderConfirmPost')
            ->with($requestModel);

        $this->sut->confirm($requestDto);
    }

    public function test_cancelOrder_sends_cancel_order_request(): void
    {
        $orderNumber = 'orderNumber';

        $this->paymentsApi
            ->expects($this->once())
            ->method('apiV1PaymentsInvoiceOrderCancelExternalOrderIdPost')
            ->with($orderNumber);

        $this->sut->cancelOrder($orderNumber);
    }

    public function test_refund_sends_refund_request(): void
    {
        $requestDto = $this->createMock(RefundRequestDto::class);
        $requestModel = $this->createMock(AxytosApiModelsRefundRequestModel::class);

        $this->mapper
            ->method('toOpenApiModel')
            ->with($requestDto, AxytosApiModelsRefundRequestModel::class)
            ->willReturn($requestModel);

        $this->paymentsApi
            ->expects($this->once())
            ->method('apiV1PaymentsInvoiceOrderRefundPost')
            ->with($requestModel);

        $this->sut->refund($requestDto);
    }

    public function test_createInvoice_sends_refund_request(): void
    {
        $requestDto = $this->createMock(CreateInvoiceRequestDto::class);
        $requestModel = $this->createMock(AxytosApiModelsInvoiceCreationModel::class);

        $this->mapper
            ->method('toOpenApiModel')
            ->with($requestDto, AxytosApiModelsInvoiceCreationModel::class)
            ->willReturn($requestModel);

        $this->paymentsApi
            ->expects($this->once())
            ->method('apiV1PaymentsInvoiceOrderCreateInvoicePost')
            ->with($requestModel);

        $this->sut->createInvoice($requestDto);
    }

    public function test_return_sends_return_request(): void
    {
        $requestDto = $this->createMock(ReturnRequestModelDto::class);
        $requestModel = $this->createMock(AxytosApiModelsReturnRequestModel::class);

        $this->mapper
            ->method('toOpenApiModel')
            ->with($requestDto, AxytosApiModelsReturnRequestModel::class)
            ->willReturn($requestModel);

        $this->paymentsApi
            ->expects($this->once())
            ->method('apiV1PaymentsInvoiceOrderReturnPost')
            ->with($requestModel);
        
        $this->sut->return($requestDto);
    }

    public function test_payment_returns_response_data_transfer_object(): void
    {
        $paymentId = 'paymentId';
        $responseDto = $this->createMock(PaymentResponseDto::class);
        $responseModel = $this->createMock(AxytosApiModelsPaymentResponseModel::class);

        $this->mapper
            ->method('toDataTransferObject')
            ->with($responseModel, PaymentResponseDto::class)
            ->willReturn($responseDto);

        $this->paymentApi
            ->expects($this->once())
            ->method('apiV1PaymentIdGet')
            ->with($paymentId)
            ->willReturn($responseModel);

        $actual = $this->sut->payment($paymentId);

        $this->assertSame($responseDto, $actual);
    }
}
