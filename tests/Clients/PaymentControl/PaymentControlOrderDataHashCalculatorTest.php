<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Clients\PaymentControl;

use Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderData;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderDataHashCalculator;
use Axytos\ECommerce\DataTransferObjects\CompanyDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketPositionDtoCollection;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PaymentControlOrderDataHashCalculatorTest extends TestCase
{
    private PaymentControlOrderDataHashCalculator $sut;

    private PaymentControlOrderData $paymentControlOrderData;

    public function setUp(): void
    {
        $this->sut = new PaymentControlOrderDataHashCalculator();

        $personalData = new CustomerDataDto();
        $personalData->dateOfBirth = new DateTimeImmutable('07-01-2022');
        $personalData->email = 'email';
        $personalData->externalCustomerId = 'externalCustomerId';

        $personalData->company = new CompanyDto();
        $personalData->company->name = 'company';

        $invoiceAddress = new InvoiceAddressDto();
        $invoiceAddress->addressLine1 = 'addressLine1';
        $invoiceAddress->addressLine2 = 'addressLine2';
        $invoiceAddress->addressLine3 = 'addressLine3';
        $invoiceAddress->addressLine4 = 'addressLine4';
        $invoiceAddress->city = 'city';
        $invoiceAddress->company = 'company';
        $invoiceAddress->country = 'country';
        $invoiceAddress->firstname = 'firstname';
        $invoiceAddress->lastname = 'lastname';
        $invoiceAddress->region = 'region';
        $invoiceAddress->salutation = 'salutation';
        $invoiceAddress->vatId = 'vatId';
        $invoiceAddress->zipCode = 'zipCode';

        $deliveryAddress = new DeliveryAddressDto();
        $deliveryAddress->addressLine1 = 'addressLine1';
        $deliveryAddress->addressLine2 = 'addressLine2';
        $deliveryAddress->addressLine3 = 'addressLine3';
        $deliveryAddress->addressLine4 = 'addressLine4';
        $deliveryAddress->city = 'city';
        $deliveryAddress->company = 'company';
        $deliveryAddress->country = 'country';
        $deliveryAddress->firstname = 'firstname';
        $deliveryAddress->lastname = 'lastname';
        $deliveryAddress->region = 'region';
        $deliveryAddress->salutation = 'salutation';
        $deliveryAddress->vatId = 'vatId';
        $deliveryAddress->zipCode = 'zipCode';

        $basket = new PaymentControlBasketDto();
        $basket->currency = 'currency';
        $basket->grossTotal = 4.4;
        $basket->netTotal = 3.2;

        $position = new PaymentControlBasketPositionDto();
        $position->productId = 'productId';
        $position->productName = 'productName';
        $position->productCategory = 'productCategory';
        $position->quantity = 5;
        $position->taxPercent = 19.9;
        $position->netPositionTotal = 8.8;
        $position->grossPositionTotal = 3.2;
        $basket->positions = new PaymentControlBasketPositionDtoCollection(...[$position]);

        $paymentControlOrderData = new PaymentControlOrderData();
        $paymentControlOrderData->personalData = $personalData;
        $paymentControlOrderData->invoiceAddress = $invoiceAddress;
        $paymentControlOrderData->deliveryAddress = $deliveryAddress;
        $paymentControlOrderData->basket = $basket;
        $paymentControlOrderData->paymentMethodId = 'paymentMethodId';

        $this->paymentControlOrderData = $paymentControlOrderData;
    }

    public function test_computeOrderDataHash_computes_hash_correctly(): void
    {
        $actual = $this->sut->computeOrderDataHash($this->paymentControlOrderData);
        $expected = '8c5e31327671d7edc4e0ab61597e5d40c6bbc5a6b5325c641742964116ee2a0a';
        $this->assertEquals($expected, $actual);
    }

    public function test_computeOrderDataHash_ignores_paymentMethodId(): void
    {
        $hash1 = $this->sut->computeOrderDataHash($this->paymentControlOrderData);

        $this->paymentControlOrderData->paymentMethodId = 'anotherPaymentMethodId';
        $hash2 = $this->sut->computeOrderDataHash($this->paymentControlOrderData);
        
        $this->assertEquals($hash1, $hash2);
    }
}
