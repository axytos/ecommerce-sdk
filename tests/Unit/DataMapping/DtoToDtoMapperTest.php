<?php

namespace Axytos\ECommerce\Tests\Unit\DataMapping;

use Axytos\ECommerce\DataMapping\DtoToDtoMapper;
use Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes\TestDto1;
use Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes\TestDto2;
use Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes\TestDto3;
use Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes\TestDto4;
use Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes\TestDtoCollection1;
use Axytos\ECommerce\Tests\Unit\DataMapping\SampleTypes\TestDtoCollection2;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class DtoToDtoMapperTest extends TestCase
{
    /**
     * @var DtoToDtoMapper
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
        $this->sut = new DtoToDtoMapper();
    }

    /**
     * @return void
     */
    public function test_dto_mapping()
    {
        $fromDto = new TestDto1();
        $fromDto->both = 1;
        $fromDto->differentType = 'differentType';
        $fromDto->from = 'fromValue';

        $toDto = new TestDto2();
        $toDto->both = $fromDto->both;
        $toDtoClassString = get_class($toDto);

        $actual = $this->sut->mapDto($fromDto, $toDtoClassString);

        $this->assertEquals($toDto, $actual);
    }

    /**
     * @return void
     */
    public function test_dto_collection_mapping()
    {
        $fromDto = new TestDto3();
        $fromDto->both = 'bothValue';
        $fromDto->from = 'fromValue';
        $fromDtoCollection = new TestDtoCollection1(...[$fromDto]);
        $fromDtoCollection::$classString = get_class($fromDto);

        $toDto = new TestDto4();
        $toDto->both = $fromDto->both;
        $toDtoCollection = new TestDtoCollection2(...[$toDto]);
        $toDtoCollection::$classString = get_class($toDto);
        $toDtoCollectionClassString = get_class($toDtoCollection);

        $actual = $this->sut->mapDtoCollection($fromDtoCollection, $toDtoCollectionClassString);

        $this->assertEquals($toDtoCollection, $actual);
    }
}
