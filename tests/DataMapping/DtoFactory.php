<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\DataMapping;

use Axytos\ECommerce\DataTransferObjects\BasketDto;
use Axytos\ECommerce\DataTransferObjects\BasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\BasketPositionDtoCollection;
use Axytos\ECommerce\DataTransferObjects\CompanyDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketPositionDtoCollection;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceRequestDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDtoCollection;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketPositionDtoCollection;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection;
use Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\TransactionMetadataDto;
use DateTime;
use DateTimeImmutable;

require_once __DIR__.'/DtoFactory.php';

class DtoFactory
{
    public static function createErrorRequestModelDto(): ErrorRequestModelDto
    {
        $errorRequest = new ErrorRequestModelDto();
        $errorRequest->title = 'title';
        $errorRequest->description = 'description';
        $errorRequest->timeStamp = self::createDateTime();
        
        return $errorRequest;
    }

    public static function createOrderCreateRequestDto(): OrderCreateRequestDto
    {
        $request = new OrderCreateRequestDto();
        $request->externalOrderId = 'externalOrderId';
        $request->date = self::createDateTime();
        $request->personalData = self::createCustomerDataDto();
        $request->invoiceAddress = self::createInvoiceAddressDto();
        $request->deliveryAddress = self::createDeliveryAddressDto();
        $request->basket = self::createBasketDto();
        $request->orderPrecheckResponse = self::createOrderPreCheckResponseDto();

        return $request;
    }

    public static function createOrderPreCheckRequestDto(): OrderPreCheckRequestDto
    {
        $request = new OrderPreCheckRequestDto();
        $request->requestMode = 'requestMode';
        $request->proofOfInterest = 'proofOfInterest';
        $request->paymentTypeSecurity = 'paymentTypeSecurity';
        $request->selectedPaymentType = 'selectedPaymentType';
        $request->personalData = self::createCustomerDataDto();
        $request->invoiceAddress = self::createInvoiceAddressDto();
        $request->deliveryAddress = self::createDeliveryAddressDto();
        $request->basket = self::createBasketDto();
        $request->orderPrecheckResponse = self::createOrderPreCheckResponseDto();

        return $request;
    }

    public static function createOrderPreCheckResponseDto(): OrderPreCheckResponseDto
    {
        $response = new OrderPreCheckResponseDto();
        $response->approvedPaymentTypeSecurities = ['A', 'B', 'C'];
        $response->processId = 'processId';
        $response->decision = 'decision';
        $response->step = 'step';
        $response->transactionMetadata = self::createTransactionMetadataDto();

        return $response;
    }

    public static function createPaymentControlCheckRequestDto(): PaymentControlCheckRequestDto
    {
        $checkRequest = new PaymentControlCheckRequestDto();
        $checkRequest->requestMode = 'requestMode';
        $checkRequest->proofOfInterest = 'proofOfInterest';
        $checkRequest->paymentTypeSecurity = 'paymentTypeSecurity';
        $checkRequest->personalData = self::createCustomerDataDto();
        $checkRequest->invoiceAddress = self::createInvoiceAddressDto();
        $checkRequest->deliveryAddress = self::createDeliveryAddressDto();
        $checkRequest->basket = self::createPaymentControlBasketDto();
        $checkRequest->paymentControlResponse = self::createPaymentControlCheckResponseDto();

        return $checkRequest;
    }

    public static function createPaymentControlConfirmRequestDto(): PaymentControlConfirmRequestDto
    {
        $checkRequest = new PaymentControlConfirmRequestDto();
        $checkRequest->paymentTypeSecurity = 'paymentTypeSecurity';
        $checkRequest->personalData = self::createCustomerDataDto();
        $checkRequest->invoiceAddress = self::createInvoiceAddressDto();
        $checkRequest->deliveryAddress = self::createDeliveryAddressDto();
        $checkRequest->basket = self::createPaymentControlBasketDto();
        $checkRequest->paymentControlResponse = self::createPaymentControlCheckResponseDto();

        return $checkRequest;
    }

    public static function createPaymentControlCheckResponseDto(): PaymentControlCheckResponseDto
    {
        $checkResponse = new PaymentControlCheckResponseDto();
        $checkResponse->approvedPaymentTypeSecurities = ['A', 'B', 'C'];
        $checkResponse->processId = 'processId';
        $checkResponse->decision = 'decision';
        $checkResponse->step = 'step';
        $checkResponse->transactionMetadata = self::createTransactionMetadataDto();

        return $checkResponse;
    }

    public static function createTransactionMetadataDto(): TransactionMetadataDto
    {
        $transactionMetadata = new TransactionMetadataDto();
        $transactionMetadata->transactionId = 'transactionId';
        $transactionMetadata->transactionInfoSignature = 'transactionInfoSignature';
        $transactionMetadata->transactionTimestamp = self::createDateTime();
        $transactionMetadata->transactionExpirationTimestamp = self::createDateTime();

        return $transactionMetadata;
    }

    public static function createCustomerDataDto(): CustomerDataDto
    {
        $customer = new CustomerDataDto();
        $customer->externalCustomerId = null;
        $customer->email = 'email';
        $customer->dateOfBirth = self::createDateTime();
        $customer->company = self::createCompanyDto();

        return $customer;
    }

    public static function createCompanyDto(): CompanyDto
    {
        $company = new CompanyDto();
        $company->name = 'company';

        return $company;
    }

    public static function createBasketDto(): BasketDto
    {
        $basket = new BasketDto();
        $basket->netTotal = 12.3;
        $basket->grossTotal = 44.5;
        $basket->currency = 'currency';
        $basket->positions = new BasketPositionDtoCollection(...self::createBasketPositionDtos());

        return $basket;
    }

    public static function createBasketPositionDtos(): array
    {
        $basketPositions = [];

        for ($i=0; $i < 3; $i++) 
        { 
            $basketPosition = self::createBasketPositionDto($i);
            array_push($basketPositions, $basketPosition);
        }

        return $basketPositions;
    }

    public static function createBasketPositionDto(?int $id = null): BasketPositionDto
    {
        $basketPosition = new BasketPositionDto();
        $basketPosition->productId = "productId$id";
        $basketPosition->productName = "productName$id";
        $basketPosition->productCategory = "productCategory$id";
        $basketPosition->quantity = 5;
        $basketPosition->taxPercent = 0.19;
        $basketPosition->netPositionTotal = 12.35;
        $basketPosition->grossPositionTotal = 55.66;

        return $basketPosition;
    }
    
    public static function createPaymentControlBasketDto(): PaymentControlBasketDto
    {
        $basket = new PaymentControlBasketDto();
        $basket->netTotal = 12.3;
        $basket->grossTotal = 44.5;
        $basket->currency = 'currency';
        $basket->positions = new PaymentControlBasketPositionDtoCollection(...self::createPaymentControlBasketPositionDtos());

        return $basket;
    }

    public static function createPaymentControlBasketPositionDtos(): array
    {
        $basketPositions = [];

        for ($i=0; $i < 3; $i++) 
        { 
            $basketPosition = self::createPaymentControlBasketPositionDto($i);
            array_push($basketPositions, $basketPosition);
        }

        return $basketPositions;
    }

    public static function createPaymentControlBasketPositionDto(?int $id = null): PaymentControlBasketPositionDto
    {
        $basketPosition = new PaymentControlBasketPositionDto();
        $basketPosition->productId = "productId$id";
        $basketPosition->productName = "productName$id";
        $basketPosition->productCategory = "productCategory$id";
        $basketPosition->quantity = 5;
        $basketPosition->taxPercent = 0.19;
        $basketPosition->netPositionTotal = 12.35;
        $basketPosition->grossPositionTotal = 55.66;

        return $basketPosition;
    }

    public static function createCreateInvoiceRequestDto(): CreateInvoiceRequestDto
    {
        $request = new CreateInvoiceRequestDto();
        $request->basket = self::createCreateInvoiceBasketDto();
        $request->dueDateOffsetDays = 1;
        $request->externalInvoiceNumber = 'externalInvoiceNumber';
        $request->externalOrderId = 'externalOrderId';
        $request->externalSubOrderId = 'externalSubOrderId';

        return $request;
    }
    
    public static function createCreateInvoiceBasketDto(): CreateInvoiceBasketDto
    {
        $basket = new CreateInvoiceBasketDto();
        $basket->netTotal = 12.3;
        $basket->grossTotal = 44.5;
        $basket->positions = new CreateInvoiceBasketPositionDtoCollection(...self::createCreateInvoiceBasketPositionDtos());
        $basket->taxGroups = new CreateInvoiceTaxGroupDtoCollection(...self::createCreateInvoiceTaxGroupDtos());

        return $basket;
    }

    public static function createCreateInvoiceBasketPositionDtos(): array
    {
        $basketPositions = [];

        for ($i=0; $i < 3; $i++) 
        { 
            $basketPosition = self::createCreateInvoiceBasketPositionDto($i);
            array_push($basketPositions, $basketPosition);
        }

        return $basketPositions;
    }

    public static function createCreateInvoiceBasketPositionDto(?int $id = null): CreateInvoiceBasketPositionDto
    {
        $basketPosition = new CreateInvoiceBasketPositionDto();
        $basketPosition->productId = "productId$id";
        $basketPosition->quantity = 5;
        $basketPosition->taxPercent = 0.19;
        $basketPosition->netPricePerUnit = 2.47;
        $basketPosition->grossPricePerUnit = 11.132;
        $basketPosition->netPositionTotal = 12.35;
        $basketPosition->grossPositionTotal = 55.66;

        return $basketPosition;
    }

    public static function createCreateInvoiceTaxGroupDtos(): array
    {
        $taxGroups = [];

        for ($i=0; $i < 3; $i++) 
        { 
            $taxGroup = self::createCreateInvoiceTaxGroupDto();
            array_push($taxGroups, $taxGroup);
        }

        return $taxGroups;
    }

    public static function createCreateInvoiceTaxGroupDto(): CreateInvoiceTaxGroupDto
    {
        $taxGroup = new CreateInvoiceTaxGroupDto();
        $taxGroup->taxPercent = 0.19;
        $taxGroup->valueToTax = 12.35;
        $taxGroup->total = 55.66;

        return $taxGroup;
    }

    public static function createDeliveryAddressDto(): DeliveryAddressDto
    {
        $deliveryAddress = new DeliveryAddressDto();
        $deliveryAddress->company = 'company';
        $deliveryAddress->salutation = 'salutation';
        $deliveryAddress->firstname = 'firstname';
        $deliveryAddress->lastname = 'lastname';
        $deliveryAddress->zipCode = 'zipCode';
        $deliveryAddress->city = 'city';
        $deliveryAddress->region = 'region';
        $deliveryAddress->country = 'co';
        $deliveryAddress->vatId = 'vatId';
        $deliveryAddress->addressLine1 = 'addressLine1';
        $deliveryAddress->addressLine2 = 'addressLine2';
        $deliveryAddress->addressLine3 = 'addressLine3';
        $deliveryAddress->addressLine4 = 'addressLine4';

        return $deliveryAddress;
    }

    public static function createInvoiceAddressDto(): InvoiceAddressDto
    {
        $invoiceAddress = new InvoiceAddressDto();
        $invoiceAddress->company = 'company';
        $invoiceAddress->salutation = 'salutation';
        $invoiceAddress->firstname = 'firstname';
        $invoiceAddress->lastname = 'lastname';
        $invoiceAddress->zipCode = 'zipCode';
        $invoiceAddress->city = 'city';
        $invoiceAddress->region = 'region';
        $invoiceAddress->country = 'co';
        $invoiceAddress->vatId = 'vatId';
        $invoiceAddress->addressLine1 = 'addressLine1';
        $invoiceAddress->addressLine2 = 'addressLine2';
        $invoiceAddress->addressLine3 = 'addressLine3';
        $invoiceAddress->addressLine4 = 'addressLine4';

        return $invoiceAddress;
    }

    public static function createReturnRequestModelDto(): ReturnRequestModelDto
    {
        $dto = new ReturnRequestModelDto();
        $dto->externalOrderId = 'externalOrderId';
        $dto->externalSubOrderId = 'externalSubOrderId';
        $dto->returnDate = self::createDateTime();
        $dto->positions = new ReturnPositionModelDtoCollection(...self::createReturnPositionModelDtos());
        return $dto;
    }

    public static function createReturnPositionModelDtos(): array
    {
        $dtos = [];

        for ($i=0; $i < 3; $i++) 
        { 
            $dto = self::createReturnPositionModelDto();
            array_push($dtos, $dto);
        }

        return $dtos;
    }

    public static function createReturnPositionModelDto(): ReturnPositionModelDto
    {
        $dto = new ReturnPositionModelDto();
        $dto->quantityToReturn = 5;
        $dto->productId = 'productId';
        return $dto;
    }

    public static function createDateTime(): DateTimeImmutable
    {
        $now = date_format(new DateTime(), DateTime::ATOM); // now with less precision, i.e. only seconds

        /**
         * @phpstan-ignore-next-line
         */
        return DateTimeImmutable::createFromFormat(DateTime::ATOM, $now);
    }
}
