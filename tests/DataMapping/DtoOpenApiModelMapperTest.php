<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\DataMapping;

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
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDto;
use Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\TransactionMetadataDto;
use Axytos\FinancialServicesAPI\Client\Model\AxytosApiModelsErrorRequestModel;
use Axytos\FinancialServicesAPI\Client\Model\AxytosApiModelsInvoiceCreationModel;
use Axytos\FinancialServicesAPI\Client\Model\AxytosApiModelsReturnRequestModel;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonModelsOrderReturnPositionModel;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsCommonCompanyRequestModel;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsCommonCustomerDataRequestModel;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsCommonDeliveryAddress;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsCommonInvoiceAddress;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsInvoiceInvoiceBasket;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsInvoiceInvoiceBasketPosition;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsInvoiceInvoiceBasketTaxGroup;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsOrderBasket;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsOrderBasketPosition;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderCreateRequest;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsOrderOrderPreCheckRequest;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlOrderPrecheckResponse;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlBasket;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlBasketPosition;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlConfirmRequest;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlRequest;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlResponse;
use Axytos\FinancialServicesAPI\Client\Model\AxytosCommonPublicAPITransactionTransactionMetadata;
use Axytos\FinancialServicesAPI\Client\Model\ModelInterface;
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
        ];
    }

    /**
     * @dataProvider dataProvider_test_all_mappings_tested
     */
    public function test_all_mappings_tested(string $dtoClass): void
    {
        $dtoClasses = array_map(function($mappingTestCase){
            return $mappingTestCase[0];
        }, $this->mappingTestCases());

        $this->assertContains($dtoClass, $dtoClasses, "Missing test case for DTO mapping of '$dtoClass'!");
    }

    public function dataProvider_test_all_mappings_tested(): array
    {
        return array_map(function($dtoClass){
            return [$dtoClass];
        }, array_filter(get_declared_classes(), function($class){
            return is_subclass_of($class, DtoInterface::class, true);
        }));
    }
}
