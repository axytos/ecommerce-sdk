<?php

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
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto;
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
use Axytos\ECommerce\DataTransferObjects\ShippingTrackingInformationRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\TransactionMetadataDto;
use Axytos\ECommerce\DataTransferObjects\UpdateOrderModelDto;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsErrorRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsInvoiceCreationModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsPaymentResponseModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsPaymentStateResponseModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsRefundRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsReportShippingModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsReturnRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsShippingTrackingInformationRequestModel;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsUpdateOrderModel;
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
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPITransactionTransactionMetadata;
use Axytos\FinancialServices\OpenAPI\Client\Model\ModelInterface;
use PHPUnit\Framework\TestCase;

class DtoOpenApiModelMapperTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->sut = new DtoOpenApiModelMapper();
    }

    /**
     * @dataProvider mappingTestCases
     * @phpstan-param class-string<DtoInterface> $dtoClassName
     * @phpstan-param class-string<Modelinterface> $modelClassName
     * @phpstan-param DtoInterface $expectedDto
     * @param string $dtoClassName
     * @param string $modelClassName
     * @param \Axytos\ECommerce\DataMapping\DtoInterface $expectedDto
     * @return void
     */
    public function test_mapping($dtoClassName, $modelClassName, $expectedDto)
    {
        /** @var ModelInterface */
        $model = $this->sut->toOpenApiModel($expectedDto, $modelClassName);

        /** @var DtoInterface */
        $dto = $this->sut->toDataTransferObject($model, $dtoClassName);

        $this->assertNotSame($expectedDto, $dto);
        $this->assertEquals($expectedDto, $dto);
    }

    /**
     * @return mixed[]
     */
    public function mappingTestCases()
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
            [CreateInvoiceRequestDto::class, AxytosApiModelsInvoiceCreationModel::class, DtoFactory::createCreateInvoiceRequestDto()],
            [CreateInvoiceBasketDto::class, AxytosCommonPublicAPIModelsInvoiceInvoiceBasket::class, DtoFactory::createCreateInvoiceBasketDto()],
            [CreateInvoiceBasketPositionDto::class, AxytosCommonPublicAPIModelsInvoiceInvoiceBasketPosition::class, DtoFactory::createCreateInvoiceBasketPositionDto()],
            [CreateInvoiceTaxGroupDto::class, AxytosCommonPublicAPIModelsInvoiceInvoiceBasketTaxGroup::class, DtoFactory::createCreateInvoiceTaxGroupDto()],
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
            [ShippingTrackingInformationRequestModelDto::class, AxytosApiModelsShippingTrackingInformationRequestModel::class, DtoFactory::createShippingTrackingInformationRequestModelDto()],
            [UpdateOrderModelDto::class, AxytosApiModelsUpdateOrderModel::class, DtoFactory::createUpdateOrderModelDto()],
        ];
    }

    /**
     * @dataProvider dataProvider_test_all_mappings_tested
     * @param string $dtoClass
     * @return void
     */
    public function test_all_mappings_tested($dtoClass)
    {
        $dtoClasses = array_map(function ($mappingTestCase) {
            return is_array($mappingTestCase) ? $mappingTestCase[0] : null;
        }, $this->mappingTestCases());

        $this->assertContains($dtoClass, $dtoClasses, "Missing test case for DTO mapping of '$dtoClass'!");
    }

    /**
     * @return mixed[]
     */
    public function dataProvider_test_all_mappings_tested()
    {
        return array_map(function ($dtoClass) {
            return [$dtoClass];
        }, $this->getDeclaredDataTransferObjectClasses());
    }

    /**
     * @return mixed[]
     */
    private function getDeclaredDataTransferObjectClasses()
    {
        $this->loadDataTransferObjects();

        return array_filter(get_declared_classes(), function ($class) {
            return is_subclass_of($class, DtoInterface::class, true);
        });
    }

    /**
     * @return void
     */
    private function loadDataTransferObjects()
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
