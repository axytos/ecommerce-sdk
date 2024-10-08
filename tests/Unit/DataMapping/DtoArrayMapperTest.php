<?php

namespace Axytos\ECommerce\Tests\Unit\DataMapping;

use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataMapping\DtoInterface;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/DtoFactory.php';

/**
 * @internal
 */
class DtoArrayMapperTest extends TestCase
{
    /**
     * @var DtoArrayMapper
     */
    private $sut;

    /**
     * @return void
     *
     * @before
     */
    #[Before]
    public function beforeEach()
    {
        $this->sut = new DtoArrayMapper();
    }

    /**
     * @dataProvider mappingTestCases
     *
     * @param DtoInterface $dto
     *
     * @return void
     */
    #[DataProvider('mappingTestCases')]
    public function test_mapping($dto)
    {
        /** @phpstan-var class-string<DtoInterface> */
        $dtoClass = get_class($dto);

        $array = $this->sut->toArray($dto);
        $actual = $this->sut->fromArray($array, $dtoClass);

        $this->assertNotSame($dto, $actual);
        $this->assertEquals($dto, $actual);
    }

    /**
     * @return mixed[]
     */
    public static function mappingTestCases()
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
            [DtoFactory::createTransactionMetadataDto()],
        ];
    }
}
