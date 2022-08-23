<?php declare(strict_types=1);

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
    function precheck(OrderPreCheckRequestDto $request): OrderPreCheckResponseDto;

    function confirm(OrderCreateRequestDto $request): void;

    function cancelOrder(string $orderNumber): void;

    function createInvoice(CreateInvoiceRequestDto $requestDto): void;

    function reportShipping(ReportShippingDto $reportDto): void;

    function refund(RefundRequestDto $request): void;

    function return(ReturnRequestModelDto $requestDto): void;

    function payment(string $paymentId): PaymentResponseDto;

    function paymentState(string $orderId): PaymentStateResponseDto;
}
