<?php

namespace Axytos\ECommerce\Clients\Invoice;

use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\CheckDecisions;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentTypeSecurities;
use Axytos\ECommerce\DataTransferObjects\RefundRequestDto;
use Axytos\ECommerce\DataTransferObjects\ReportShippingDto;
use Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\ShippingTrackingInformationRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\UpdateOrderModelDto;
use Exception;

class InvoiceClient implements InvoiceClientInterface
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceApiInterface
     */
    private $invoiceApi;
    /**
     * @var \Axytos\ECommerce\DataMapping\DtoArrayMapper
     */
    private $dtoArrayMapper;

    public function __construct(
        InvoiceApiInterface $invoiceApi,
        DtoArrayMapper $dtoArrayMapper
    ) {
        $this->invoiceApi = $invoiceApi;
        $this->dtoArrayMapper = $dtoArrayMapper;
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return string
     */
    public function precheck($orderContext)
    {
        $requestDto = new OrderPreCheckRequestDto();
        $requestDto->requestMode = 'SingleStep';
        $requestDto->proofOfInterest = 'AAE';
        $requestDto->selectedPaymentType = 'INVOICE';
        $requestDto->paymentTypeSecurity = PaymentTypeSecurities::UNSAFE;
        $requestDto->personalData = $orderContext->getPersonalData();
        $requestDto->invoiceAddress = $orderContext->getInvoiceAddress();
        $requestDto->deliveryAddress = $orderContext->getDeliveryAddress();
        $requestDto->basket = $orderContext->getBasket();

        $responseDto = $this->invoiceApi->precheck($requestDto);

        $preCheckResponseData = $this->dtoArrayMapper->toArray($responseDto);
        $orderContext->setPreCheckResponseData($preCheckResponseData);

        if (in_array($responseDto->decision, [CheckDecisions::SAFE, CheckDecisions::REJECT], true)) {
            return ShopActions::CHANGE_PAYMENT_METHOD;
        }

        return ShopActions::COMPLETE_ORDER;
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function confirmOrder($orderContext)
    {
        $requestDto = new OrderCreateRequestDto();
        $requestDto->externalOrderId = $orderContext->getOrderNumber();
        $requestDto->date = $orderContext->getOrderDateTime();
        $requestDto->personalData = $orderContext->getPersonalData();
        $requestDto->invoiceAddress = $orderContext->getInvoiceAddress();
        $requestDto->deliveryAddress = $orderContext->getDeliveryAddress();
        $requestDto->basket = $orderContext->getBasket();

        $preCheckResponseData = $orderContext->getPreCheckResponseData();
        $preCheckResponse = $this->dtoArrayMapper->fromArray($preCheckResponseData, OrderPreCheckResponseDto::class);
        $requestDto->orderPrecheckResponse = $preCheckResponse;

        $this->invoiceApi->confirm($requestDto);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function cancelOrder($orderContext)
    {
        $this->invoiceApi->cancelOrder($orderContext->getOrderNumber());
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function uncancelOrder($orderContext)
    {
        $this->invoiceApi->uncancelOrder($orderContext->getOrderNumber());
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function createInvoice($orderContext)
    {
        $requestDto = new CreateInvoiceRequestDto();
        $requestDto->basket = $orderContext->getCreateInvoiceBasket();
        $requestDto->externalInvoiceNumber = $orderContext->getOrderInvoiceNumber();
        $requestDto->externalOrderId = $orderContext->getOrderNumber();

        $this->invoiceApi->createInvoice($requestDto);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function reportShipping($orderContext)
    {
        $reportDto = new ReportShippingDto();
        $reportDto->externalOrderId = $orderContext->getOrderNumber();
        $reportDto->basketPositions = $orderContext->getShippingBasketPositions();

        $this->invoiceApi->reportShipping($reportDto);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function trackingInformation($orderContext)
    {
        $trackingIds = $orderContext->getTrackingIds();

        /** @var string $trackingId */
        foreach ($trackingIds as $trackingId) {
            $trackingInformationDto = new ShippingTrackingInformationRequestModelDto();
            $trackingInformationDto->trackingId = $trackingId;
            $trackingInformationDto->externalOrderId = $orderContext->getOrderNumber();
            $trackingInformationDto->deliveryWeight = $orderContext->getDeliveryWeight();
            $trackingInformationDto->logistician = $orderContext->getLogistician();
            $trackingInformationDto->deliveryAddress = $orderContext->getDeliveryAddress();

            $this->invoiceApi->trackingInformation($trackingInformationDto);
        }
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function refund($orderContext)
    {
        $requestDto = new RefundRequestDto();
        $requestDto->externalOrderId = $orderContext->getOrderNumber();
        $requestDto->originalInvoiceNumber = $orderContext->getOrderInvoiceNumber();
        $requestDto->basket = $orderContext->getRefundBasket();

        $this->invoiceApi->refund($requestDto);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function returnOrder($orderContext)
    {
        $requestDto = new ReturnRequestModelDto();
        $requestDto->externalOrderId = $orderContext->getOrderNumber();
        $requestDto->positions = $orderContext->getReturnPositions();
        $this->invoiceApi->returnOrder($requestDto);
    }

    /**
     * @param string $paymentId
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderPaymentUpdate
     */
    public function getInvoiceOrderPaymentUpdate($paymentId)
    {
        $invoiceOrderPaymentUpdate = new InvoiceOrderPaymentUpdate();
        $invoiceOrderPaymentUpdate->orderId = $this->getOrderIdFromPayment($paymentId);
        $invoiceOrderPaymentUpdate->paymentStatus = $this->getPaymentStateForOrderId($invoiceOrderPaymentUpdate->orderId);
        return $invoiceOrderPaymentUpdate;
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function updateOrder($orderContext)
    {
        $updateOrderModel = new UpdateOrderModelDto();
        $updateOrderModel->externalOrderId = $orderContext->getOrderNumber();
        $updateOrderModel->basket = $orderContext->getBasket();

        $this->invoiceApi->updateOrder($updateOrderModel);
    }

    /**
     * @param string $paymentId
     * @return string
     */
    private function getOrderIdFromPayment($paymentId)
    {
        $paymentId = (string) $paymentId;
        $paymentResponse = $this->invoiceApi->payment($paymentId);

        $externalOrderId = $paymentResponse->externalOrderId;

        if (is_null($externalOrderId)) {
            throw new Exception('ExternalOrderId not found');
        }

        return $externalOrderId;
    }

    /**
     * @param string $orderId
     * @return string
     */
    private function getPaymentStateForOrderId($orderId)
    {
        $orderId = (string) $orderId;
        $paymentState = $this->invoiceApi->paymentState($orderId)->paymentState;

        if (is_null($paymentState)) {
            throw new Exception('PaymentState not found');
        }

        return $paymentState;
    }

    /**
     *
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return bool
     */
    public function hasBeenPaid($orderContext)
    {
        $paymentStatus = $this->getPaymentStateForOrderId($orderContext->getOrderNumber());

        return $paymentStatus === PaymentStatus::PAID
            || $paymentStatus === PaymentStatus::OVERPAID;
    }
}
