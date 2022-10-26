<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Invoice;

use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentResponseDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentStateResponseDto;
use Axytos\ECommerce\DataTransferObjects\RefundRequestDto;
use Axytos\ECommerce\DataTransferObjects\ReportShippingDto;
use Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto;
use Axytos\FinancialServices\OpenAPI\Client\Api\PaymentsApi;
use Axytos\FinancialServices\OpenAPI\Client\Api\PaymentApi;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsInvoiceCreationModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsRefundRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsReportShippingModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsReturnRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderCreateRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest;

class InvoiceApiAdapter implements InvoiceApiInterface
{
    private PaymentsApi $paymentsApi;
    private PaymentApi $paymentApi;
    private DtoOpenApiModelMapper $mapper;

    public function __construct(
        PaymentsApi $paymentsApi,
        PaymentApi $paymentApi,
        DtoOpenApiModelMapper $mapper
    ) {
        $this->paymentsApi = $paymentsApi;
        $this->paymentApi = $paymentApi;
        $this->mapper = $mapper;
    }

    public function precheck(OrderPreCheckRequestDto $requestDto): OrderPreCheckResponseDto
    {
        $request = $this->mapper->toOpenApiModel($requestDto, AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest::class);

        $response = $this->paymentsApi->apiV1PaymentsInvoiceOrderPrecheckPost($request);

        return $this->mapper->toDataTransferObject($response, OrderPreCheckResponseDto::class);
    }

    public function confirm(OrderCreateRequestDto $requestDto): void
    {
        $request = $this->mapper->toOpenApiModel($requestDto, AxytosCommonPublicAPIModelsOrderOrderCreateRequest::class);

        $this->paymentsApi->apiV1PaymentsInvoiceOrderConfirmPost($request);
    }

    public function cancelOrder(string $orderNumber): void
    {
        $this->paymentsApi->apiV1PaymentsInvoiceOrderCancelExternalOrderIdPost($orderNumber);
    }

    public function createInvoice(CreateInvoiceRequestDto $requestDto): void
    {
        $request = $this->mapper->toOpenApiModel($requestDto, AxytosApiModelsInvoiceCreationModel::class);

        $this->paymentsApi->apiV1PaymentsInvoiceOrderCreateInvoicePost($request);
    }

    public function reportShipping(ReportShippingDto $reportDto): void
    {
        $report = $this->mapper->toOpenApiModel($reportDto, AxytosApiModelsReportShippingModel::class);
        $this->paymentsApi->apiV1PaymentsInvoiceOrderReportshippingPost($report);
    }

    public function refund(RefundRequestDto $requestDto): void
    {
        $report = $this->mapper->toOpenApiModel($requestDto, AxytosApiModelsRefundRequestModel::class);
        $this->paymentsApi->apiV1PaymentsInvoiceOrderRefundPost($report);
    }

    public function return(ReturnRequestModelDto $requestDto): void
    {
        $request = $this->mapper->toOpenApiModel($requestDto, AxytosApiModelsReturnRequestModel::class);
        $this->paymentsApi->apiV1PaymentsInvoiceOrderReturnPost($request);
    }

    public function payment(string $paymentId): PaymentResponseDto
    {
        $response = $this->paymentApi->apiV1PaymentIdGet($paymentId);
        return $this->mapper->toDataTransferObject($response, PaymentResponseDto::class);
    }

    public function paymentState(string $orderId): PaymentStateResponseDto
    {
        $response = $this->paymentsApi->apiV1PaymentsInvoiceOrderPaymentstateExternalOrderIdGet($orderId);
        return $this->mapper->toDataTransferObject($response, PaymentStateResponseDto::class);
    }
}
