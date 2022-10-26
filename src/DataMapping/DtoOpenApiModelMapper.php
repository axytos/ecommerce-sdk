<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataMapping;

use Axytos\ECommerce\DataTransferObjects\BasketDto;
use Axytos\ECommerce\DataTransferObjects\BasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\CompanyDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceRequestDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceTaxGroupDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentStateResponseDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\RefundBasketTaxGroupDto;
use Axytos\ECommerce\DataTransferObjects\RefundRequestDto;
use Axytos\ECommerce\DataTransferObjects\ReportShippingDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDto;
use Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\ShippingBasketPositionDto;
use Axytos\ECommerce\DataTransferObjects\TransactionMetadataDto;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsErrorRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsInvoiceCreationModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsPaymentResponseModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsPaymentStateResponseModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsRefundRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsReportShippingModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsReturnRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonModelsOrderRefundBasket;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonModelsOrderRefundBasketTaxGroup;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonModelsOrderRefundPositionModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonModelsOrderReturnPositionModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsCommonCompanyRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsCommonCustomerDataRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsCommonDeliveryAddress;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsCommonInvoiceAddress;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsInvoiceInvoiceBasket;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsInvoiceInvoiceBasketPosition;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsInvoiceInvoiceBasketTaxGroup;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderBasket;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderBasketPosition;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderCreateRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsOrderShippingBasketPosition;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlOrderPrecheckResponse;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlBasket;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlBasketPosition;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlConfirmRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlResponse;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPITransactionTransactionMetadata;
use Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface;
use DateTimeInterface;
use InvalidArgumentException;
use ReflectionClass;

class DtoOpenApiModelMapper
{
    /** @var array<string,string> */
    private const MAPPINGS = [
        CustomerDataDto::class => AxytosCommonPublicAPIModelsCommonCustomerDataRequestModel::class,
        CompanyDto::class => AxytosCommonPublicAPIModelsCommonCompanyRequestModel::class,
        InvoiceAddressDto::class => AxytosCommonPublicAPIModelsCommonInvoiceAddress::class,
        DeliveryAddressDto::class => AxytosCommonPublicAPIModelsCommonDeliveryAddress::class,
        ErrorRequestModelDto::class => AxytosApiModelsErrorRequestModel::class,
        BasketDto::class => AxytosCommonPublicAPIModelsOrderBasket::class,
        BasketPositionDto::class => AxytosCommonPublicAPIModelsOrderBasketPosition::class,
        OrderCreateRequestDto::class => AxytosCommonPublicAPIModelsOrderOrderCreateRequest::class,
        OrderPreCheckRequestDto::class => AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest::class,
        OrderPreCheckResponseDto::class => AxytosCommonPublicAPIModelsPaymentControlOrderPrecheckResponse::class,
        PaymentControlBasketDto::class => AxytosCommonPublicAPIModelsPaymentControlPaymentControlBasket::class,
        PaymentControlBasketPositionDto::class => AxytosCommonPublicAPIModelsPaymentControlPaymentControlBasketPosition::class,
        PaymentControlCheckRequestDto::class => AxytosCommonPublicAPIModelsPaymentControlPaymentControlRequest::class,
        PaymentControlCheckResponseDto::class => AxytosCommonPublicAPIModelsPaymentControlPaymentControlResponse::class,
        PaymentControlConfirmRequestDto::class => AxytosCommonPublicAPIModelsPaymentControlPaymentControlConfirmRequest::class,
        CreateInvoiceRequestDto::class => AxytosApiModelsInvoiceCreationModel::class,
        CreateInvoiceBasketDto::class => AxytosCommonPublicAPIModelsInvoiceInvoiceBasket::class,
        CreateInvoiceBasketPositionDto::class => AxytosCommonPublicAPIModelsInvoiceInvoiceBasketPosition::class,
        CreateInvoiceTaxGroupDto::class => AxytosCommonPublicAPIModelsInvoiceInvoiceBasketTaxGroup::class,
        TransactionMetadataDto::class => AxytosCommonPublicAPITransactionTransactionMetadata::class,
        ReportShippingDto::class => AxytosApiModelsReportShippingModel::class,
        ShippingBasketPositionDto::class => AxytosCommonPublicAPIModelsOrderShippingBasketPosition::class,
        RefundRequestDto::class => AxytosApiModelsRefundRequestModel::class,
        RefundBasketDto::class => AxytosCommonModelsOrderRefundBasket::class,
        RefundBasketPositionDto::class => AxytosCommonModelsOrderRefundPositionModel::class,
        RefundBasketTaxGroupDto::class => AxytosCommonModelsOrderRefundBasketTaxGroup::class,
        ReturnRequestModelDto::class => AxytosApiModelsReturnRequestModel::class,
        ReturnPositionModelDto::class => AxytosCommonModelsOrderReturnPositionModel::class,
        PaymentStateResponseDto::class => AxytosApiModelsPaymentStateResponseModel::class,
        PaymentResponseDto::class => AxytosApiModelsPaymentResponseModel::class,
    ];

    private DtoOpenApiModelModelMappings $mappings;

    public function __construct()
    {
        $this->mappings = new DtoOpenApiModelModelMappings(self::MAPPINGS);
    }

    /**
     * @phpstan-template T of ModelInterface
     * @phpstan-param DtoInterface $dataTransferObject
     * @phpstan-param class-string<T> $openApiModelName
     * @phpstan-return T
     */
    public function toOpenApiModel(DtoInterface $dataTransferObject, string $openApiModelName): ModelInterface
    {
        $dtoClassName = get_class($dataTransferObject);

        if (!is_subclass_of($openApiModelName, ModelInterface::class)) {
            throw new InvalidArgumentException("$openApiModelName does not implement " . ModelInterface::class);
        }

        if (!$this->mappings->hasMapping($dtoClassName, $openApiModelName)) {
            throw new InvalidArgumentException("Undefined mapping: {$dtoClassName} - {$openApiModelName}");
        }

        $dtoReflector = new ReflectionClass($dataTransferObject);

        /** @phpstan-var T */
        $openApiModel = new $openApiModelName();

        $attributeInfos = OpenApiModelAttributeInfo::getAttributeInfos($openApiModel);

        foreach ($attributeInfos as $attributeInfo) {
            if (!$dtoReflector->hasProperty($attributeInfo->getName())) {
                continue;
            }

            $oaAttributeName = $attributeInfo->getName();
            $dtoPropertyInfo = DtoPropertyInfo::create($dtoReflector->getProperty($oaAttributeName));
            $dtoValue = $dtoPropertyInfo->getValue($dataTransferObject);
            $oaValue = $this->toOpenApiValue($dtoValue);
            $attributeInfo->setValue($openApiModel, $oaValue);
        }

        return $openApiModel;
    }

    /**
     * @phpstan-template T of DtoInterface
     * @phpstan-param ModelInterface $openApiModel
     * @phpstan-param class-string<T> $dataTransferObjectName
     * @phpstan-return T
     */
    public function toDataTransferObject(ModelInterface $openApiModel, string $dataTransferObjectName): DtoInterface
    {
        $openApiModelName = get_class($openApiModel);

        if (!is_subclass_of($dataTransferObjectName, DtoInterface::class)) {
            throw new InvalidArgumentException("$dataTransferObjectName does not implement " . DtoInterface::class);
        }

        if (!$this->mappings->hasMapping($dataTransferObjectName, $openApiModelName)) {
            throw new InvalidArgumentException("Undefined mapping: {$dataTransferObjectName} - {$openApiModelName}");
        }

        $reflector = new ReflectionClass($dataTransferObjectName);

        /** @phpstan-var T */
        $dataTransferObject = $reflector->newInstanceWithoutConstructor();

        $attributeInfos = OpenApiModelAttributeInfo::getAttributeInfos($openApiModel);

        foreach ($attributeInfos as $attributeInfo) {
            if (!$reflector->hasProperty($attributeInfo->getName())) {
                continue;
            }

            $oaAttributeName = $attributeInfo->getName();
            $dtoPropertyInfo = DtoPropertyInfo::create($reflector->getProperty($oaAttributeName));
            $oaValue = $attributeInfo->getValue($openApiModel);
            $dtoValue = $this->toDataTransferObjectValue($oaValue, $attributeInfo, $dtoPropertyInfo);
            $dtoPropertyInfo->setValue($dataTransferObject, $dtoValue);
        }

        return $dataTransferObject;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function toOpenApiValue($value)
    {
        if (is_array($value)) {
            return $this->toOpenApiArray($value);
        }

        if ($value instanceof DateTimeInterface) {
            return $this->toOpenApiDateTime($value);
        }

        if ($value instanceof DtoInterface) {
            $oaModelName = $this->mappings->lookupOpenApiModelName($value);
            return $this->toOpenApiModel($value, $oaModelName);
        }

        if ($value instanceof DtoCollection) {
            return $this->toOpenApiValue($value->getElements());
        }

        return $value;
    }

    private function toOpenApiArray(array $values): array
    {
        $models = [];

        foreach ($values as $value) {
            array_push($models, $this->toOpenApiValue($value));
        }

        return $models;
    }

    private function toOpenApiDateTime(DateTimeInterface $dateTime): string
    {
        $serializer = new DateTimeSerializer();
        return $serializer->serialize($dateTime);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function toDataTransferObjectValue($value, OpenApiModelAttributeInfo $attributeInfo, DtoPropertyInfo $dtoPropertyInfo)
    {
        if (is_array($value)) {
            if ($dtoPropertyInfo->hasDtoCollectionType()) {
                return $this->toDtoCollection($value, $dtoPropertyInfo);
            }
            return $this->toDataTransferObjectArray($value, $attributeInfo, $dtoPropertyInfo);
        }

        if (is_string($value) && $attributeInfo->getFormat() === 'date-time') {
            return $this->toDataTransferObjectDateTime($value);
        }

        if ($value instanceof ModelInterface) {
            $dtoClassName = $this->mappings->lookupDtoClassName($value);
            return $this->toDataTransferObject($value, $dtoClassName);
        }

        return $value;
    }

    private function toDtoCollection(array $values, DtoPropertyInfo $dtoPropertyInfo): DtoCollection
    {
        /** @phpstan-var class-string<DtoCollection> */
        $dtoCollectionClassName = $dtoPropertyInfo->getType();

        /** @phpstan-var class-string<DtoInterface> */
        $dataTransferObjectName = $dtoCollectionClassName::getElementClass();

        $elements = array_map(function ($x) use ($dataTransferObjectName) {
            return $this->toDataTransferObject($x, $dataTransferObjectName);
        }, $values);

        return new $dtoCollectionClassName(...$elements);
    }

    private function toDataTransferObjectArray(array $values, OpenApiModelAttributeInfo $attributeInfo, DtoPropertyInfo $dtoPropertyInfo): array
    {
        $dtos = [];

        foreach ($values as $value) {
            array_push($dtos, $this->toDataTransferObjectValue($value, $attributeInfo, $dtoPropertyInfo));
        }

        return $dtos;
    }

    private function toDataTransferObjectDateTime(string $value): DateTimeInterface
    {
        $serializer = new DateTimeSerializer();
        return $serializer->deserialize($value);
    }
}
