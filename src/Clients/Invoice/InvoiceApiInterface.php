<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Invoice;

use Axytos\ECommerce\DataTransferObjects\RefundRequestDto;
use Axytos\ECommerce\DataTransferObjects\ReportShippingDto;
use Axytos\ECommerce\DataTransferObjects\PaymentResponseDto;
use Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto;
use Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentStateResponseDto;

interface InvoiceApiInterface
{
    public function precheck(OrderPreCheckRequestDto $request): OrderPreCheckResponseDto;

    public function confirm(OrderCreateRequestDto $request): void;

    public function cancelOrder(string $orderNumber): void;

    public function createInvoice(CreateInvoiceRequestDto $requestDto): void;

    public function reportShipping(ReportShippingDto $reportDto): void;

    public function refund(RefundRequestDto $request): void;

    public function return(ReturnRequestModelDto $requestDto): void;

    public function payment(string $paymentId): PaymentResponseDto;

    public function paymentState(string $orderId): PaymentStateResponseDto;
}
