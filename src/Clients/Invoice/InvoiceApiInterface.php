<?php

namespace Axytos\ECommerce\Clients\Invoice;

interface InvoiceApiInterface
{
    /**
     * @param \Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto $request
     *
     * @return \Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto
     */
    public function precheck($request);

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto $request
     *
     * @return void
     */
    public function confirm($request);

    /**
     * @param string $orderNumber
     *
     * @return void
     */
    public function cancelOrder($orderNumber);

    /**
     * @param string $orderNumber
     *
     * @return void
     */
    public function uncancelOrder($orderNumber);

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\CreateInvoiceRequestDto $requestDto
     *
     * @return void
     */
    public function createInvoice($requestDto);

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ReportShippingDto $reportDto
     *
     * @return void
     */
    public function reportShipping($reportDto);

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ShippingTrackingInformationRequestModelDto $trackingInformationDto
     *
     * @return void
     */
    public function trackingInformation($trackingInformationDto);

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\RefundRequestDto $request
     *
     * @return void
     */
    public function refund($request);

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto $requestDto
     *
     * @return void
     */
    public function returnOrder($requestDto);

    /**
     * @param string $paymentId
     *
     * @return \Axytos\ECommerce\DataTransferObjects\PaymentResponseDto
     */
    public function payment($paymentId);

    /**
     * @param string $orderId
     *
     * @return \Axytos\ECommerce\DataTransferObjects\PaymentStateResponseDto
     */
    public function paymentState($orderId);

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\UpdateOrderModelDto $updateOrderModelDto
     *
     * @return void
     */
    public function updateOrder($updateOrderModelDto);
}
