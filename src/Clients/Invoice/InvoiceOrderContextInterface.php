<?php

namespace Axytos\ECommerce\Clients\Invoice;

interface InvoiceOrderContextInterface
{
    /**
     * @return string
     */
    public function getOrderNumber();

    /**
     * @return string
     */
    public function getOrderInvoiceNumber();

    /**
     * @return \DateTimeInterface
     */
    public function getOrderDateTime();

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\CustomerDataDto
     */
    public function getPersonalData();

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto
     */
    public function getInvoiceAddress();

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto
     */
    public function getDeliveryAddress();

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\BasketDto
     */
    public function getBasket();

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\RefundBasketDto
     */
    public function getRefundBasket();

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto
     */
    public function getCreateInvoiceBasket();

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection
     */
    public function getShippingBasketPositions();

    /**
     * @return mixed[]
     */
    public function getPreCheckResponseData();

    /**
     * @param mixed[] $data
     *
     * @return void
     */
    public function setPreCheckResponseData($data);

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection
     */
    public function getReturnPositions();

    /**
     * @return float
     */
    public function getDeliveryWeight();

    /**
     * @return string[]
     */
    public function getTrackingIds();

    /**
     * @return string
     */
    public function getLogistician();
}
