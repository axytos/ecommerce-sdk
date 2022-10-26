<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\DataMapping;

use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataMapping\DtoInterface;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/DtoFactory.php';

class DtoArrayMapperTest extends TestCase
{
    private DtoArrayMapper $sut;

    public function setUp(): void
    {
        $this->sut = new DtoArrayMapper();
    }

    /**
     * @dataProvider mappingTestCases
     */
    public function test_mapping(DtoInterface $dto): void
    {
        /** @phpstan-var class-string<DtoInterface> */
        $dtoClass = get_class($dto);

        $array = $this->sut->toArray($dto);
        $actual = $this->sut->fromArray($array, $dtoClass);

        $this->assertNotSame($dto, $actual);
        $this->assertEquals($dto, $actual);
    }

    public function mappingTestCases(): array
    {
        return [
            [DtoFactory::createCustomerDataDto()],
            [DtoFactory::createCompanyDto()],
            [DtoFactory::createInvoiceAddressDto()],
            [DtoFactory::createDeliveryAddressDto()],
            [DtoFactory::createErrorRequestModelDto()],
            [DtoFactory::createBasketDto()],
            [DtoFactory::createBasketPositionDto()],
            [DtoFactory::createOrderCreateRequestDto()],
            [DtoFactory::createOrderPreCheckRequestDto()],
            [DtoFactory::createOrderPreCheckResponseDto()],
            [DtoFactory::createPaymentControlBasketDto()],
            [DtoFactory::createPaymentControlBasketPositionDto()],
            [DtoFactory::createPaymentControlCheckRequestDto()],
            [DtoFactory::createPaymentControlCheckResponseDto()],
            [DtoFactory::createPaymentControlConfirmRequestDto()],
            [DtoFactory::createTransactionMetadataDto()],
        ];
    }
}
