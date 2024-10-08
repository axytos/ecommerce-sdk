<?php

namespace Axytos\ECommerce\Tests\Integration\Fakes;

use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;

class InvoiceOrderContextFake implements InvoiceOrderContextInterface
{
    /**
     * @var string
     */
    private $orderNumber;
    /**
     * @var string
     */
    private $orderInvoiceNumber;
    /**
     * @var \DateTimeInterface
     */
    private $orderDateTime;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\CustomerDataDto
     */
    private $personalData;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto
     */
    private $invoiceAddress;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto
     */
    private $deliveryAddress;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\BasketDto
     */
    private $basket;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\RefundBasketDto
     */
    private $refundBasket;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto
     */
    private $createInvoiceBasket;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection
     */
    private $shippingBasketPositions;
    /**
     * @var \Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection
     */
    private $returnPositions;
    /**
     * @var float
     */
    private $deliveryWeight;
    /**
     * @var string[]
     */
    private $trackingIds;
    /**
     * @var string
     */
    private $logistician;
    /**
     * @var string
     */
    private $deliveryInformation;

    /**
     * @var mixed[]
     */
    private $preCheckResponseData = [];

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     *
     * @return void
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }

    /**
     * @return string
     */
    public function getOrderInvoiceNumber()
    {
        return $this->orderInvoiceNumber;
    }

    /**
     * @param string $invoiceNumber
     *
     * @return void
     */
    public function setOrderInvoiceNumber($invoiceNumber)
    {
        $this->orderInvoiceNumber = $invoiceNumber;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getOrderDateTime()
    {
        return $this->orderDateTime;
    }

    /**
     * @param \DateTimeInterface $orderDateTime
     *
     * @return void
     */
    public function setOrderDateTime($orderDateTime)
    {
        $this->orderDateTime = $orderDateTime;
    }

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\CustomerDataDto
     */
    public function getPersonalData()
    {
        return $this->personalData;
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\CustomerDataDto $personalData
     *
     * @return void
     */
    public function setPersonalData($personalData)
    {
        $this->personalData = $personalData;
    }

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto
     */
    public function getInvoiceAddress()
    {
        return $this->invoiceAddress;
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto $invoiceAddress
     *
     * @return void
     */
    public function setInvoiceAddress($invoiceAddress)
    {
        $this->invoiceAddress = $invoiceAddress;
    }

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto $deliveryAddress
     *
     * @return void
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\BasketDto
     */
    public function getBasket()
    {
        return $this->basket;
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\BasketDto $basket
     *
     * @return void
     */
    public function setBasket($basket)
    {
        $this->basket = $basket;
    }

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\RefundBasketDto
     */
    public function getRefundBasket()
    {
        return $this->refundBasket;
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\RefundBasketDto $refundBasket
     *
     * @return void
     */
    public function setRefundBasket($refundBasket)
    {
        $this->refundBasket = $refundBasket;
    }

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto
     */
    public function getCreateInvoiceBasket()
    {
        return $this->createInvoiceBasket;
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto $createInvoiceBasket
     *
     * @return void
     */
    public function setCreateInvoiceBasket($createInvoiceBasket)
    {
        $this->createInvoiceBasket = $createInvoiceBasket;
    }

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection
     */
    public function getShippingBasketPositions()
    {
        return $this->shippingBasketPositions;
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection $shippingBasketPositions
     *
     * @return void
     */
    public function setShippingBasketPositions($shippingBasketPositions)
    {
        $this->shippingBasketPositions = $shippingBasketPositions;
    }

    /**
     * @return mixed[]
     */
    public function getPreCheckResponseData()
    {
        return $this->preCheckResponseData;
    }

    /**
     * @param mixed[] $data
     *
     * @return void
     */
    public function setPreCheckResponseData($data)
    {
        $this->preCheckResponseData = $data;
    }

    /**
     * @return \Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection
     */
    public function getReturnPositions()
    {
        return $this->returnPositions;
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection $returnPositions
     *
     * @return void
     */
    public function setReturnPositions($returnPositions)
    {
        $this->returnPositions = $returnPositions;
    }

    /**
     * @return float
     */
    public function getDeliveryWeight()
    {
        return $this->deliveryWeight;
    }

    /**
     * @param float $deliveryWeight
     *
     * @return void
     */
    public function setDeliveryWeight($deliveryWeight)
    {
        $this->deliveryWeight = $deliveryWeight;
    }

    /**
     * @return string[]
     */
    public function getTrackingIds()
    {
        return $this->trackingIds;
    }

    /**
     * @param string[] $trackingIds
     *
     * @return void
     */
    public function setTrackingIds($trackingIds)
    {
        $this->trackingIds = $trackingIds;
    }

    /**
     * @return string
     */
    public function getLogistician()
    {
        return $this->logistician;
    }

    /**
     * @param string $logistician
     *
     * @return void
     */
    public function setLogistician($logistician)
    {
        $this->logistician = $logistician;
    }

    /**
     * @return string
     */
    public function getDeliveryInformation()
    {
        return $this->deliveryInformation;
    }

    /**
     * @param string $deliveryInformation
     *
     * @return void
     */
    public function setDeliveryInformation($deliveryInformation)
    {
        $this->deliveryInformation = $deliveryInformation;
    }
}
