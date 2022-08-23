<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\DataMapping;

use Axytos\ECommerce\DataMapping\DtoCollection;
use Axytos\ECommerce\DataMapping\DtoInterface;
use Axytos\ECommerce\DataMapping\DtoToDtoMapper;
use PHPUnit\Framework\TestCase;

class DtoToDtoMapperTest extends TestCase
{
    private DtoToDtoMapper $sut;

    public function setUp(): void
    {
        $this->sut = new DtoToDtoMapper();
    }

    public function test_dto_mapping(): void
    {
        $fromDto = new class implements DtoInterface {
            public ?int $both;
            public ?string $differentType;
            public ?string $from;
        };
        $fromDto->both = 1;
        $fromDto->differentType = "differentType";
        $fromDto->from = "fromValue";

        $toDto = new class implements DtoInterface {
            public ?int $both;
            public ?int $differentType;
            public ?string $to;
        };
        $toDto->both = $fromDto->both;
        $toDtoClassString = get_class($toDto);

        $actual = $this->sut->mapDto($fromDto, $toDtoClassString);

        $this->assertEquals($toDto, $actual);
    }

    public function test_dto_collection_mapping(): void
    {
        $fromDto = new class implements DtoInterface {
            public ?string $both;
            public ?string $from;
        };
        $fromDto->both = "bothValue";
        $fromDto->from = "fromValue";
        $fromDtoCollection = new class(...[$fromDto]) extends DtoCollection  {
            /** @phpstan-var class-string<DtoInterface> */
            public static string $classString;
            public static function getElementClass(): string
            {
                return self::$classString;
            }   
            public function __construct(DtoInterface ...$values)
            {
                parent::__construct($values);
            }
        };
        $fromDtoCollection::$classString = get_class($fromDto);

        $toDto = new class implements DtoInterface {
            public ?string $both;
            public ?string $to;
        };
        $toDto->both = $fromDto->both;
        $toDtoCollection = new class(...[$toDto]) extends DtoCollection  {
            /** @phpstan-var class-string<DtoInterface> */
            public static string $classString;
            public static function getElementClass(): string
            {
                return self::$classString;
            }   
            public function __construct(DtoInterface ...$values)
            {
                parent::__construct($values);
            }
        };
        $toDtoCollection::$classString = get_class($toDto);
        $toDtoCollectionClassString = get_class($toDtoCollection);

        $actual = $this->sut->mapDtoCollection($fromDtoCollection, $toDtoCollectionClassString);

        $this->assertEquals($toDtoCollection, $actual);
    }
}
