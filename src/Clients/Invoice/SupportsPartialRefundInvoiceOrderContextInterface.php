<?php

namespace Axytos\ECommerce\Clients\Invoice;

interface SupportsPartialRefundInvoiceOrderContextInterface
{
    /**
     * @return \Axytos\ECommerce\DataTransferObjects\RefundBasketDto
     */
    public function getPartialRefundBasket();
}
