<?php

declare(strict_types=1);

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
use Exception;

class InvoiceClient implements InvoiceClientInterface
{
    private InvoiceApiInterface $invoiceApi;
    private DtoArrayMapper $dtoArrayMapper;

    public function __construct(
        InvoiceApiInterface $invoiceApi,
        DtoArrayMapper $dtoArrayMapper
    ) {
        $this->invoiceApi = $invoiceApi;
        $this->dtoArrayMapper = $dtoArrayMapper;
    }

    public function precheck(InvoiceOrderContextInterface $orderContext): string
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

        if (in_array($responseDto->decision, [CheckDecisions::SAFE, CheckDecisions::REJECT])) {
            return ShopActions::CHANGE_PAYMENT_METHOD;
        }

        return ShopActions::COMPLETE_ORDER;
    }

    public function confirmOrder(InvoiceOrderContextInterface $orderContext): void
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

    public function cancelOrder(InvoiceOrderContextInterface $orderContext): void
    {
        $this->invoiceApi->cancelOrder($orderContext->getOrderNumber());
    }

    public function createInvoice(InvoiceOrderContextInterface $orderContext): void
    {
        $requestDto = new CreateInvoiceRequestDto();
        $requestDto->basket = $orderContext->getCreateInvoiceBasket();
        $requestDto->externalInvoiceNumber = $orderContext->getOrderInvoiceNumber();
        $requestDto->externalOrderId = $orderContext->getOrderNumber();

        $this->invoiceApi->createInvoice($requestDto);
    }

    public function reportShipping(InvoiceOrderContextInterface $orderContext): void
    {
        $reportDto = new ReportShippingDto();
        $reportDto->externalOrderId = $orderContext->getOrderNumber();
        $reportDto->basketPositions = $orderContext->getShippingBasketPositions();

        $this->invoiceApi->reportShipping($reportDto);
    }

    public function refund(InvoiceOrderContextInterface $orderContext): void
    {
        $requestDto = new RefundRequestDto();
        $requestDto->externalOrderId = $orderContext->getOrderNumber();
        $requestDto->originalInvoiceNumber = $orderContext->getOrderInvoiceNumber();
        $requestDto->basket = $orderContext->getRefundBasket();

        $this->invoiceApi->refund($requestDto);
    }

    public function return(InvoiceOrderContextInterface $orderContext): void
    {
        $requestDto = new ReturnRequestModelDto();
        $requestDto->externalOrderId = $orderContext->getOrderNumber();
        $requestDto->positions = $orderContext->getReturnPositions();
        $this->invoiceApi->return($requestDto);
    }

    public function getInvoiceOrderPaymentUpdate(string $paymentId): InvoiceOrderPaymentUpdate
    {
        $invoiceOrderPaymentUpdate = new InvoiceOrderPaymentUpdate();
        $invoiceOrderPaymentUpdate->orderId = $this->getOrderIdFromPayment($paymentId);
        $invoiceOrderPaymentUpdate->paymentStatus = $this->getPaymentStateForOrderId($invoiceOrderPaymentUpdate->orderId);
        return $invoiceOrderPaymentUpdate;
    }

    private function getOrderIdFromPayment(string $paymentId): string
    {
        $paymentResponse = $this->invoiceApi->payment($paymentId);

        $externalOrderId = $paymentResponse->externalOrderId;

        if (is_null($externalOrderId)) {
            throw new Exception('ExternalOrderId not found');
        }

        return $externalOrderId;
    }

    private function getPaymentStateForOrderId(string $orderId): string
    {
        $paymentState = $this->invoiceApi->paymentState($orderId)->paymentState;

        if (is_null($paymentState)) {
            throw new Exception('PaymentState not found');
        }

        return $paymentState;
    }
}
