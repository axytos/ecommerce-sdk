<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataMapping\DtoInterface;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;

class PaymentControlOrderDataHashCalculator
{
    /**
     * @var HashAlgorithmInterface
     */
    private $hashAlgorithm;
    /**
     * @var \Axytos\ECommerce\DataMapping\DtoArrayMapper
     */
    private $dtoArrayMapper;

    public function __construct(HashAlgorithmInterface $hashAlgorithm)
    {
        $this->hashAlgorithm = $hashAlgorithm;
        $this->dtoArrayMapper = new DtoArrayMapper();
    }

    /**
     * @param \Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderData $data
     * @return string
     */
    public function computeOrderDataHash($data)
    {
        $hash = '';

        $hash = $this->computeHash($hash . $this->hasCustomerData($data->personalData));
        $hash = $this->computeHash($hash . $this->hashInvoiceAddress($data->invoiceAddress));
        $hash = $this->computeHash($hash . $this->hashDeliveryAddress($data->deliveryAddress));
        $hash = $this->computeHash($hash . $this->hashPaymentControlBasket($data->basket));
        return $hash;
    }

    /**
     * @return string
     */
    private function hasCustomerData(CustomerDataDto $customerDataDto)
    {
        return $this->computeDtoHash($customerDataDto);
    }

    /**
     * @return string
     */
    private function hashInvoiceAddress(InvoiceAddressDto $invoiceAddress)
    {
        return $this->computeDtoHash($invoiceAddress);
    }

    /**
     * @return string
     */
    private function hashDeliveryAddress(DeliveryAddressDto $deliveryAddress)
    {
        return $this->computeDtoHash($deliveryAddress);
    }

    /**
     * @return string
     */
    private function hashPaymentControlBasket(PaymentControlBasketDto $paymentControlBasket)
    {
        return $this->computeDtoHash($paymentControlBasket);
    }

    /**
     * @return string
     */
    private function computeDtoHash(DtoInterface $dto)
    {
        $arrayDto = $this->dtoArrayMapper->toArray($dto);
        $serializedDto = serialize($arrayDto);
        return $this->computeHash($serializedDto);
    }

    /**
     * @param string $input
     * @return string
     */
    private function computeHash($input)
    {
        return $this->hashAlgorithm->compute($input);
    }
}
