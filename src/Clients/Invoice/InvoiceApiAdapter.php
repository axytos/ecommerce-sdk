<?php

namespace Axytos\ECommerce\Clients\Invoice;

use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentStateResponseDto;
use Axytos\FinancialServices\OpenAPI\Client\Api\PaymentsApi;
use Axytos\FinancialServices\OpenAPI\Client\Api\PaymentApi;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsInvoiceCreationModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsRefundRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsReportShippingModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsReturnRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsShippingTrackingInformationRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsUpdateOrderModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderCreateRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest;

class InvoiceApiAdapter implements InvoiceApiInterface
{
    /**
     * @var \Axytos\FinancialServices\OpenAPI\Client\Api\PaymentsApi
     */
    private $paymentsApi;
    /**
     * @var \Axytos\FinancialServices\OpenAPI\Client\Api\PaymentApi
     */
    private $paymentApi;
    /**
     * @var \Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper
     */
    private $mapper;

    public function __construct(
        PaymentsApi $paymentsApi,
        PaymentApi $paymentApi,
        DtoOpenApiModelMapper $mapper
    ) {
        $this->paymentsApi = $paymentsApi;
        $this->paymentApi = $paymentApi;
        $this->mapper = $mapper;
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto $requestDto
     * @return \Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto
     */
    public function precheck($requestDto)
    {
        $request = $this->mapper->toOpenApiModel($requestDto, AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest::class);

        $response = $this->paymentsApi->apiV1PaymentsInvoiceOrderPrecheckPost($request);

        return $this->mapper->toDataTransferObject($response, OrderPreCheckResponseDto::class);
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto $requestDto
     * @return void
     */
    public function confirm($requestDto)
    {
        $request = $this->mapper->toOpenApiModel($requestDto, AxytosCommonPublicAPIModelsOrderOrderCreateRequest::class);

        $this->paymentsApi->apiV1PaymentsInvoiceOrderConfirmPost($request);
    }

    /**
     * @param string $orderNumber
     * @return void
     */
    public function cancelOrder($orderNumber)
    {
        $this->paymentsApi->apiV1PaymentsInvoiceOrderCancelExternalOrderIdPost($orderNumber);
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\CreateInvoiceRequestDto $requestDto
     * @return void
     */
    public function createInvoice($requestDto)
    {
        $request = $this->mapper->toOpenApiModel($requestDto, AxytosApiModelsInvoiceCreationModel::class);

        $this->paymentsApi->apiV1PaymentsInvoiceOrderCreateInvoicePost($request);
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ReportShippingDto $reportDto
     * @return void
     */
    public function reportShipping($reportDto)
    {
        $report = $this->mapper->toOpenApiModel($reportDto, AxytosApiModelsReportShippingModel::class);
        $this->paymentsApi->apiV1PaymentsInvoiceOrderReportshippingPost($report);
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ShippingTrackingInformationRequestModelDto $trackingInformationDto
     * @return void
     */
    public function trackingInformation($trackingInformationDto)
    {
        $request = $this->mapper->toOpenApiModel($trackingInformationDto, AxytosApiModelsShippingTrackingInformationRequestModel::class);
        $this->paymentsApi->apiV1PaymentsInvoiceOrderTrackingInformationPost($request);
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\RefundRequestDto $requestDto
     * @return void
     */
    public function refund($requestDto)
    {
        $report = $this->mapper->toOpenApiModel($requestDto, AxytosApiModelsRefundRequestModel::class);
        $this->paymentsApi->apiV1PaymentsInvoiceOrderRefundPost($report);
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto $requestDto
     * @return void
     */
    public function returnOrder($requestDto)
    {
        $request = $this->mapper->toOpenApiModel($requestDto, AxytosApiModelsReturnRequestModel::class);
        $this->paymentsApi->apiV1PaymentsInvoiceOrderReturnPost($request);
    }

    /**
     * @param string $paymentId
     * @return \Axytos\ECommerce\DataTransferObjects\PaymentResponseDto
     */
    public function payment($paymentId)
    {
        $response = $this->paymentApi->apiV1PaymentIdGet($paymentId);
        return $this->mapper->toDataTransferObject($response, PaymentResponseDto::class);
    }

    /**
     * @param string $orderId
     * @return \Axytos\ECommerce\DataTransferObjects\PaymentStateResponseDto
     */
    public function paymentState($orderId)
    {
        $response = $this->paymentsApi->apiV1PaymentsInvoiceOrderPaymentstateExternalOrderIdGet($orderId);
        return $this->mapper->toDataTransferObject($response, PaymentStateResponseDto::class);
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\UpdateOrderModelDto $updateOrderModelDto
     * @return void
     */
    public function updateOrder($updateOrderModelDto)
    {
        $request = $this->mapper->toOpenApiModel($updateOrderModelDto, AxytosApiModelsUpdateOrderModel::class);
        $this->paymentsApi->apiV1PaymentsInvoiceOrderUpdatePost($request);
    }
}
