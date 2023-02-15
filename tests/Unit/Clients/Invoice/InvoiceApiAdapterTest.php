<?php

namespace Axytos\ECommerce\Tests\Unit\Clients\Invoice;

use PHPUnit\Framework\TestCase;
use Axytos\FinancialServices\OpenAPI\Client\Api\PaymentApi;
use Axytos\FinancialServices\OpenAPI\Client\Api\PaymentsApi;
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
use Axytos\ECommerce\DataTransferObjects\PaymentStateResponseDto;
use Axytos\ECommerce\DataTransferObjects\ReportShippingDto;
use Axytos\ECommerce\DataTransferObjects\UpdateOrderModelDto;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsInvoiceCreationModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsPaymentResponseModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsPaymentStateResponseModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsRefundRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsReportShippingModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsReturnRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsUpdateOrderModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderCreateRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlOrderPrecheckResponse;

class InvoiceApiAdapterTest extends TestCase
{
    /** @var PaymentsApi&MockObject */
    private $paymentsApi;

    /** @var PaymentApi&MockObject */
    private $paymentApi;

    /** @var DtoOpenApiModelMapper&MockObject */
    private $mapper;

    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceApiAdapter
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
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

    /**
     * @return void
     */
    public function test_precheck_returns_response_data_transfer_object()
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

    /**
     * @return void
     */
    public function test_confirm_sends_confirm_request()
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

    /**
     * @return void
     */
    public function test_cancelOrder_sends_cancel_order_request()
    {
        $orderNumber = 'orderNumber';

        $this->paymentsApi
            ->expects($this->once())
            ->method('apiV1PaymentsInvoiceOrderCancelExternalOrderIdPost')
            ->with($orderNumber);

        $this->sut->cancelOrder($orderNumber);
    }

    /**
     * @return void
     */
    public function test_refund_sends_refund_request()
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

    /**
     * @return void
     */
    public function test_createInvoice_sends_refund_request()
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

    /**
     * @return void
     */
    public function test_reportShipping_sends_refund_request()
    {
        $requestDto = $this->createMock(ReportShippingDto::class);
        $requestModel = $this->createMock(AxytosApiModelsReportShippingModel::class);

        $this->mapper
            ->method('toOpenApiModel')
            ->with($requestDto, AxytosApiModelsReportShippingModel::class)
            ->willReturn($requestModel);

        $this->paymentsApi
            ->expects($this->once())
            ->method('apiV1PaymentsInvoiceOrderReportshippingPost')
            ->with($requestModel);

        $this->sut->reportShipping($requestDto);
    }

    /**
     * @return void
     */
    public function test_return_sends_return_request()
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

        $this->sut->returnOrder($requestDto);
    }

    /**
     * @return void
     */
    public function test_payment_returns_response_data_transfer_object()
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

    /**
     * @return void
     */
    public function test_paymentState_returns_response_data_transfer_object()
    {
        $orderId = 'orderId';
        $responseDto = $this->createMock(PaymentStateResponseDto::class);
        $responseModel = $this->createMock(AxytosApiModelsPaymentStateResponseModel::class);

        $this->mapper
            ->method('toDataTransferObject')
            ->with($responseModel, PaymentStateResponseDto::class)
            ->willReturn($responseDto);

        $this->paymentsApi
            ->expects($this->once())
            ->method('apiV1PaymentsInvoiceOrderPaymentstateExternalOrderIdGet')
            ->with($orderId)
            ->willReturn($responseModel);

        $actual = $this->sut->paymentState($orderId);

        $this->assertSame($responseDto, $actual);
    }

    /**
     * @return void
     */
    public function test_updateOrder_sends_order_update_request()
    {
        $requestDto = $this->createMock(UpdateOrderModelDto::class);
        $requestModel = $this->createMock(AxytosApiModelsUpdateOrderModel::class);

        $this->mapper
            ->method('toOpenApiModel')
            ->with($requestDto, AxytosApiModelsUpdateOrderModel::class)
            ->willReturn($requestModel);

        $this->paymentsApi
            ->expects($this->once())
            ->method('apiV1PaymentsInvoiceOrderUpdatePost')
            ->with($requestModel);

        $this->sut->updateOrder($requestDto);
    }
}
