<?php

namespace Axytos\ECommerce\Tests\Unit\DataMapping;

use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataMapping\DtoInterface;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/DtoFactory.php';

class DtoArrayMapperTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\DataMapping\DtoArrayMapper
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->sut = new DtoArrayMapper();
    }

    /**
     * @dataProvider mappingTestCases
     * @param \Axytos\ECommerce\DataMapping\DtoInterface $dto
     * @return void
     */
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
    public function mappingTestCases()
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
