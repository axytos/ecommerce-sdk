<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\DataMapping;

use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataMapping\DtoInterface;
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
use PHPUnit\Framework\TestCase;

class DtoOpenApiModelMapperTest extends TestCase
{
    private DtoOpenApiModelMapper $sut;

    public function setUp(): void
    {
        $this->sut = new DtoOpenApiModelMapper();
    }

    /**
     * @dataProvider mappingTestCases
     * @phpstan-param class-string<DtoInterface> $dtoClassName
     * @phpstan-param class-string<Modelinterface> $modelClassName
     * @phpstan-param DtoInterface $expectedDto
     */
    public function test_mapping(string $dtoClassName, string $modelClassName, DtoInterface $expectedDto): void
    {
        /** @var ModelInterface */
        $model = $this->sut->toOpenApiModel($expectedDto, $modelClassName);

        /** @var DtoInterface */
        $dto = $this->sut->toDataTransferObject($model, $dtoClassName);

        $this->assertNotSame($expectedDto, $dto);
        $this->assertEquals($expectedDto, $dto);
    }

    public function mappingTestCases(): array
    {
        return [
            [CustomerDataDto::class, AxytosCommonPublicAPIModelsCommonCustomerDataRequestModel::class, DtoFactory::createCustomerDataDto()],
            [CompanyDto::class, AxytosCommonPublicAPIModelsCommonCompanyRequestModel::class, DtoFactory::createCompanyDto()],
            [InvoiceAddressDto::class, AxytosCommonPublicAPIModelsCommonInvoiceAddress::class, DtoFactory::createInvoiceAddressDto()],
            [DeliveryAddressDto::class, AxytosCommonPublicAPIModelsCommonDeliveryAddress::class, DtoFactory::createDeliveryAddressDto()],
            [ErrorRequestModelDto::class, AxytosApiModelsErrorRequestModel::class, DtoFactory::createErrorRequestModelDto()],
            [BasketDto::class, AxytosCommonPublicAPIModelsOrderBasket::class, DtoFactory::createBasketDto()],
            [BasketPositionDto::class, AxytosCommonPublicAPIModelsOrderBasketPosition::class, DtoFactory::createBasketPositionDto()],
            [OrderCreateRequestDto::class, AxytosCommonPublicAPIModelsOrderOrderCreateRequest::class, DtoFactory::createOrderCreateRequestDto()],
            [OrderPreCheckRequestDto::class, AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest::class, DtoFactory::createOrderPreCheckRequestDto()],
            [OrderPreCheckResponseDto::class, AxytosCommonPublicAPIModelsPaymentControlOrderPrecheckResponse::class, DtoFactory::createOrderPreCheckResponseDto()],
            [PaymentControlBasketDto::class, AxytosCommonPublicAPIModelsPaymentControlPaymentControlBasket::class, DtoFactory::createPaymentControlBasketDto()],
            [PaymentControlBasketPositionDto::class, AxytosCommonPublicAPIModelsPaymentControlPaymentControlBasketPosition::class, DtoFactory::createPaymentControlBasketPositionDto()],
            [CreateInvoiceRequestDto::class, AxytosApiModelsInvoiceCreationModel::class, DtoFactory::createCreateInvoiceRequestDto()],
            [CreateInvoiceBasketDto::class, AxytosCommonPublicAPIModelsInvoiceInvoiceBasket::class, DtoFactory::createCreateInvoiceBasketDto()],
            [CreateInvoiceBasketPositionDto::class, AxytosCommonPublicAPIModelsInvoiceInvoiceBasketPosition::class, DtoFactory::createCreateInvoiceBasketPositionDto()],
            [CreateInvoiceTaxGroupDto::class, AxytosCommonPublicAPIModelsInvoiceInvoiceBasketTaxGroup::class, DtoFactory::createCreateInvoiceTaxGroupDto()],
            [PaymentControlCheckRequestDto::class, AxytosCommonPublicAPIModelsPaymentControlPaymentControlRequest::class, DtoFactory::createPaymentControlCheckRequestDto()],
            [PaymentControlCheckResponseDto::class, AxytosCommonPublicAPIModelsPaymentControlPaymentControlResponse::class, DtoFactory::createPaymentControlCheckResponseDto()],
            [PaymentControlConfirmRequestDto::class, AxytosCommonPublicAPIModelsPaymentControlPaymentControlConfirmRequest::class, DtoFactory::createPaymentControlConfirmRequestDto()],
            [TransactionMetadataDto::class, AxytosCommonPublicAPITransactionTransactionMetadata::class, DtoFactory::createTransactionMetadataDto()],
            [ReturnRequestModelDto::class, AxytosApiModelsReturnRequestModel::class, DtoFactory::createReturnRequestModelDto()],
            [ReturnPositionModelDto::class, AxytosCommonModelsOrderReturnPositionModel::class, DtoFactory::createReturnPositionModelDto()],
            [PaymentResponseDto::class, AxytosApiModelsPaymentResponseModel::class, DtoFactory::createPaymentResponseDto()],
            [PaymentStateResponseDto::class, AxytosApiModelsPaymentStateResponseModel::class, DtoFactory::createPaymentStateResponseDto()],
            [RefundBasketDto::class, AxytosCommonModelsOrderRefundBasket::class, DtoFactory::createRefundBasketDto()],
            [RefundBasketPositionDto::class, AxytosCommonModelsOrderRefundPositionModel::class, DtoFactory::createRefundBasketPositoinDto()],
            [RefundBasketTaxGroupDto::class, AxytosCommonModelsOrderRefundBasketTaxGroup::class, DtoFactory::createRefundBasketTaxGroupDto()],
            [RefundRequestDto::class, AxytosApiModelsRefundRequestModel::class, DtoFactory::createRefundRequestDto()],
            [ReportShippingDto::class, AxytosApiModelsReportShippingModel::class, DtoFactory::createReportShippingDto()],
            [ShippingBasketPositionDto::class, AxytosCommonPublicAPIModelsOrderShippingBasketPosition::class, DtoFactory::createShippingBasketPositionDto()],
        ];
    }

    /**
     * @dataProvider dataProvider_test_all_mappings_tested
     */
    public function test_all_mappings_tested(string $dtoClass): void
    {
        $dtoClasses = array_map(function ($mappingTestCase) {
            return $mappingTestCase[0];
        }, $this->mappingTestCases());

        $this->assertContains($dtoClass, $dtoClasses, "Missing test case for DTO mapping of '$dtoClass'!");
    }

    public function dataProvider_test_all_mappings_tested(): array
    {
        return array_map(function ($dtoClass) {
            return [$dtoClass];
        }, $this->getDeclaredDataTransferObjectClasses());
    }

    private function getDeclaredDataTransferObjectClasses(): array
    {
        $this->loadDataTransferObjects();

        return array_filter(get_declared_classes(), function ($class) {
            return is_subclass_of($class, DtoInterface::class, true);
        });
    }

    private function loadDataTransferObjects(): void
    {
        $globPattern = __DIR__ . '/../../../src/DataTransferObjects/*.php';
        $namespacePrefix = 'Axytos\\ECommerce\\DataTransferObjects\\';

        /** @var array<string> */
        $filepaths = glob($globPattern);

        foreach ($filepaths as $filepath) {
            $pathinfo = pathinfo($filepath);
            $classname = $namespacePrefix . $pathinfo['filename'];
            class_exists($classname); // trigger autoloader
        }
    }
}
