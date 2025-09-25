<?php

namespace Axytos\ECommerce\Tests\Unit\DataMapping;

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
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderCreateRequestWithoutPrecheckDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentStateResponseDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketPositionDtoCollection;
use Axytos\ECommerce\DataTransferObjects\RefundBasketTaxGroupDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketTaxGroupDtoCollection;
use Axytos\ECommerce\DataTransferObjects\RefundRequestDto;
use Axytos\ECommerce\DataTransferObjects\ReportShippingDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection;
use Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDtoCollection;
use Axytos\ECommerce\DataTransferObjects\ShippingTrackingInformationRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\TransactionMetadataDto;
use Axytos\ECommerce\DataTransferObjects\UpdateOrderModelDto;

require_once __DIR__ . '/DtoFactory.php';

class DtoFactory
{
    /**
     * @return ErrorRequestModelDto
     */
    public static function createErrorRequestModelDto()
    {
        $errorRequest = new ErrorRequestModelDto();
        $errorRequest->title = 'title';
        $errorRequest->description = 'description';
        $errorRequest->timeStamp = self::createDateTime();

        return $errorRequest;
    }

    /**
     * @return OrderCreateRequestDto
     */
    public static function createOrderCreateRequestDto()
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

    /**
     * @return OrderCreateRequestWithoutPrecheckDto
     */
    public static function createOrderCreateRequestWithoutPrecheckDto()
    {
        $request = new OrderCreateRequestWithoutPrecheckDto();
        $request->externalOrderId = 'externalOrderId';
        $request->date = self::createDateTime();
        $request->personalData = self::createCustomerDataDto();
        $request->invoiceAddress = self::createInvoiceAddressDto();
        $request->deliveryAddress = self::createDeliveryAddressDto();
        $request->basket = self::createBasketDto();

        return $request;
    }

    /**
     * @return OrderPreCheckRequestDto
     */
    public static function createOrderPreCheckRequestDto()
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

    /**
     * @return OrderPreCheckResponseDto
     */
    public static function createOrderPreCheckResponseDto()
    {
        $response = new OrderPreCheckResponseDto();
        $response->approvedPaymentTypeSecurities = ['A', 'B', 'C'];
        $response->processId = 'processId';
        $response->decision = 'decision';
        $response->step = 'step';
        $response->transactionMetadata = self::createTransactionMetadataDto();

        return $response;
    }

    /**
     * @return TransactionMetadataDto
     */
    public static function createTransactionMetadataDto()
    {
        $transactionMetadata = new TransactionMetadataDto();
        $transactionMetadata->transactionId = 'transactionId';
        $transactionMetadata->transactionInfoSignature = 'transactionInfoSignature';
        $transactionMetadata->transactionTimestamp = self::createDateTime();
        $transactionMetadata->transactionExpirationTimestamp = self::createDateTime();

        return $transactionMetadata;
    }

    /**
     * @return CustomerDataDto
     */
    public static function createCustomerDataDto()
    {
        $customer = new CustomerDataDto();
        $customer->externalCustomerId = null;
        $customer->email = 'email';
        $customer->dateOfBirth = self::createDateTime();
        $customer->company = self::createCompanyDto();

        return $customer;
    }

    /**
     * @return CompanyDto
     */
    public static function createCompanyDto()
    {
        $company = new CompanyDto();
        $company->name = 'company';

        return $company;
    }

    /**
     * @return BasketDto
     */
    public static function createBasketDto()
    {
        $positions = array_map([self::class, 'createBasketPositionDto'], array_fill(0, 5, 0));

        $basket = new BasketDto();
        $basket->netTotal = 12.3;
        $basket->grossTotal = 44.5;
        $basket->currency = 'currency';
        $basket->positions = new BasketPositionDtoCollection(...$positions);

        return $basket;
    }

    /**
     * @param int|null $id
     *
     * @return BasketPositionDto
     */
    public static function createBasketPositionDto($id = null)
    {
        $basketPosition = new BasketPositionDto();
        $basketPosition->productId = "productId{$id}";
        $basketPosition->productName = "productName{$id}";
        $basketPosition->productCategory = "productCategory{$id}";
        $basketPosition->quantity = 5.5;
        $basketPosition->taxPercent = 0.19;
        $basketPosition->netPositionTotal = 12.35;
        $basketPosition->grossPositionTotal = 55.66;

        return $basketPosition;
    }

    /**
     * @return CreateInvoiceRequestDto
     */
    public static function createCreateInvoiceRequestDto()
    {
        $request = new CreateInvoiceRequestDto();
        $request->basket = self::createCreateInvoiceBasketDto();
        $request->dueDateOffsetDays = 1;
        $request->externalInvoiceNumber = 'externalInvoiceNumber';
        $request->externalOrderId = 'externalOrderId';
        $request->externalSubOrderId = 'externalSubOrderId';

        return $request;
    }

    /**
     * @return CreateInvoiceBasketDto
     */
    public static function createCreateInvoiceBasketDto()
    {
        $positions = array_map([self::class, 'createCreateInvoiceBasketPositionDto'], array_fill(0, 5, 0));
        $taxGroups = array_map([self::class, 'createCreateInvoiceTaxGroupDto'], array_fill(0, 5, 0));

        $basket = new CreateInvoiceBasketDto();
        $basket->netTotal = 12.3;
        $basket->grossTotal = 44.5;
        $basket->positions = new CreateInvoiceBasketPositionDtoCollection(...$positions);
        $basket->taxGroups = new CreateInvoiceTaxGroupDtoCollection(...$taxGroups);

        return $basket;
    }

    /**
     * @param int|null $id
     *
     * @return CreateInvoiceBasketPositionDto
     */
    public static function createCreateInvoiceBasketPositionDto($id = null)
    {
        $basketPosition = new CreateInvoiceBasketPositionDto();
        $basketPosition->productId = "productId{$id}";
        $basketPosition->quantity = 5;
        $basketPosition->taxPercent = 0.19;
        $basketPosition->netPricePerUnit = 2.47;
        $basketPosition->grossPricePerUnit = 11.132;
        $basketPosition->netPositionTotal = 12.35;
        $basketPosition->grossPositionTotal = 55.66;

        return $basketPosition;
    }

    /**
     * @return CreateInvoiceTaxGroupDto
     */
    public static function createCreateInvoiceTaxGroupDto()
    {
        $taxGroup = new CreateInvoiceTaxGroupDto();
        $taxGroup->taxPercent = 0.19;
        $taxGroup->valueToTax = 12.35;
        $taxGroup->total = 55.66;

        return $taxGroup;
    }

    /**
     * @return DeliveryAddressDto
     */
    public static function createDeliveryAddressDto()
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

    /**
     * @return InvoiceAddressDto
     */
    public static function createInvoiceAddressDto()
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

    /**
     * @return ReturnRequestModelDto
     */
    public static function createReturnRequestModelDto()
    {
        $positions = array_map([self::class, 'createReturnPositionModelDto'], array_fill(0, 5, 0));

        $dto = new ReturnRequestModelDto();
        $dto->externalOrderId = 'externalOrderId';
        $dto->externalSubOrderId = 'externalSubOrderId';
        $dto->returnDate = self::createDateTime();
        $dto->positions = new ReturnPositionModelDtoCollection(...$positions);

        return $dto;
    }

    /**
     * @return ReturnPositionModelDto
     */
    public static function createReturnPositionModelDto()
    {
        $dto = new ReturnPositionModelDto();
        $dto->quantityToReturn = 5;
        $dto->productId = 'productId';

        return $dto;
    }

    /**
     * @return \DateTimeImmutable
     */
    public static function createDateTime()
    {
        $now = date_format(new \DateTime(), \DateTime::ATOM); // now with less precision, i.e. only seconds

        /**
         * @phpstan-ignore-next-line
         */
        return \DateTimeImmutable::createFromFormat(\DateTime::ATOM, $now);
    }

    /**
     * @return PaymentResponseDto
     */
    public static function createPaymentResponseDto()
    {
        $dto = new PaymentResponseDto();
        $dto->id = 'id';
        $dto->date = self::createDateTime();
        $dto->invoiceNumber = 'invoiceNumber';
        $dto->externalOrderId = 'externalOrderId';
        $dto->amount = 42;
        $dto->currency = 'currency';

        return $dto;
    }

    /**
     * @return PaymentStateResponseDto
     */
    public static function createPaymentStateResponseDto()
    {
        $dto = new PaymentStateResponseDto();
        $dto->paymentState = 'paymentState';

        return $dto;
    }

    /**
     * @return RefundBasketDto
     */
    public static function createRefundBasketDto()
    {
        $positions = array_map([self::class, 'createRefundBasketPositoinDto'], array_fill(0, 5, 0));
        $taxGroups = array_map([self::class, 'createRefundBasketTaxGroupDto'], array_fill(0, 5, 0));

        $dto = new RefundBasketDto();
        $dto->netTotal = 42;
        $dto->grossTotal = 42;
        $dto->positions = new RefundBasketPositionDtoCollection(...$positions);
        $dto->taxGroups = new RefundBasketTaxGroupDtoCollection(...$taxGroups);

        return $dto;
    }

    /**
     * @return RefundBasketPositionDto
     */
    public static function createRefundBasketPositoinDto()
    {
        $dto = new RefundBasketPositionDto();
        $dto->productId = 'productId';
        $dto->netRefundTotal = 42;
        $dto->grossRefundTotal = 42;

        return $dto;
    }

    /**
     * @return RefundBasketTaxGroupDto
     */
    public static function createRefundBasketTaxGroupDto()
    {
        $dto = new RefundBasketTaxGroupDto();
        $dto->taxPercent = 29;
        $dto->valueToTax = 42;
        $dto->total = 42;

        return $dto;
    }

    /**
     * @return RefundRequestDto
     */
    public static function createRefundRequestDto()
    {
        $dto = new RefundRequestDto();
        $dto->externalOrderId = 'externalOrderId';
        $dto->originalInvoiceNumber = 'originalInvoiceNumber';
        $dto->basket = self::createRefundBasketDto();

        return $dto;
    }

    /**
     * @return ReportShippingDto
     */
    public static function createReportShippingDto()
    {
        $positions = array_map([self::class, 'createShippingBasketPositionDto'], array_fill(0, 5, 0));

        $dto = new ReportShippingDto();
        $dto->externalOrderId = 'externalOrderId';
        $dto->basketPositions = new ShippingBasketPositionDtoCollection(...$positions);

        return $dto;
    }

    /**
     * @return ShippingBasketPositionDto
     */
    public static function createShippingBasketPositionDto()
    {
        $dto = new ShippingBasketPositionDto();
        $dto->productId = 'productId';
        $dto->quantity = 5;

        return $dto;
    }

    /**
     * @return ShippingTrackingInformationRequestModelDto
     */
    public static function createShippingTrackingInformationRequestModelDto()
    {
        $dto = new ShippingTrackingInformationRequestModelDto();
        $dto->externalOrderId = 'externalOrderId';
        $dto->deliveryWeight = 42.6;
        $dto->trackingId = 'trackingId';
        $dto->logistician = 'logistician';
        $dto->deliveryInformation = 'deliveryInformation';
        $dto->deliveryAddress = self::createDeliveryAddressDto();

        return $dto;
    }

    /**
     * @return UpdateOrderModelDto
     */
    public static function createUpdateOrderModelDto()
    {
        $dto = new UpdateOrderModelDto();
        $dto->externalOrderId = 'externalOrderId';
        $dto->basket = self::createBasketDto();

        return $dto;
    }
}
