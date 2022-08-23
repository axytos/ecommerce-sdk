<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataMapping\DtoInterface;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;

class PaymentControlOrderDataHashCalculator
{
    private DtoArrayMapper $dtoArrayMapper;

    function __construct() {
        $this->dtoArrayMapper = new DtoArrayMapper();
    }

    public function computeOrderDataHash(PaymentControlOrderData $data): string
    {
        $hash = '';
        
        $hash = $this->computeHash($hash.$this->hasCustomerData($data->personalData));
        $hash = $this->computeHash($hash.$this->hashInvoiceAddress($data->invoiceAddress));
        $hash = $this->computeHash($hash.$this->hashDeliveryAddress($data->deliveryAddress));
        $hash = $this->computeHash($hash.$this->hashPaymentControlBasket($data->basket));
        return $hash;
    }

    private function hasCustomerData(CustomerDataDto $customerDataDto): string
    {
        return $this->computeDtoHash($customerDataDto);
    }

    private function hashInvoiceAddress(InvoiceAddressDto $invoiceAddress): string
    {
        return $this->computeDtoHash($invoiceAddress);
    }

    private function hashDeliveryAddress(DeliveryAddressDto $deliveryAddress): string
    {
        return $this->computeDtoHash($deliveryAddress);
    }

    private function hashPaymentControlBasket(PaymentControlBasketDto $paymentControlBasket): string
    {
        return $this->computeDtoHash($paymentControlBasket);
    }

    private function computeDtoHash(DtoInterface $dto): string {
        $arrayDto = $this->dtoArrayMapper->toArray($dto);
        $serializedDto = serialize($arrayDto);
        return $this->computeHash($serializedDto);
    }

    private function computeHash(string $input): string
    {
        return hash('sha256', $input);
    }
}
