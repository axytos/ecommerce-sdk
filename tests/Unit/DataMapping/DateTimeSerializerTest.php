<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\DataMapping;

use Axytos\ECommerce\DataMapping\DateTimeSerializationException;
use Axytos\ECommerce\DataMapping\DateTimeSerializer;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class DateTimeSerializerTest extends TestCase
{
    private DateTimeSerializer $sut;

    public function setUp(): void
    {
        $this->sut = new DateTimeSerializer();
    }

    public function test_serialize(): void
    {
        $year = 2022;
        $month = 11;
        $day = 22;
        $hour = 15;
        $minute = 29;
        $second = 47;
        $microsecond = 123445;

        $dateTime = new DateTime();
        $dateTime->setDate($year, $month, $day);
        $dateTime->setTime($hour, $minute, $second, $microsecond);

        $actual = $this->sut->serialize($dateTime);

        $this->assertEquals("{$year}-{$month}-{$day}T{$hour}:{$minute}:{$second}Z", $actual);
    }

    public function test_deserialize(): void
    {
        $year = 2022;
        $month = 11;
        $day = 22;
        $hour = 15;
        $minute = 29;
        $second = 47;
        $microsecond = 123445;

        $dateTime = new DateTime();
        $dateTime->setDate($year, $month, $day);
        $dateTime->setTime($hour, $minute, $second);

        $actual = $this->sut->deserialize("{$year}-{$month}-{$day}T{$hour}:{$minute}:{$second}.{$microsecond}Z");

        $this->assertEquals($dateTime, $actual);
    }

    public function test_deserialize_with_seven_microsecond_digits(): void
    {
        $year = 2022;
        $month = 11;
        $day = 22;
        $hour = 15;
        $minute = 29;
        $second = 47;
        $microsecond = 1234457;

        $dateTime = new DateTime();
        $dateTime->setDate($year, $month, $day);
        $dateTime->setTime($hour, $minute, $second, 0);

        $actual = $this->sut->deserialize("{$year}-{$month}-{$day}T{$hour}:{$minute}:{$second}.{$microsecond}Z");

        $this->assertEquals($dateTime, $actual);
    }

    public function test_deserialize_throws_DateTimeSerializationException_if_deserialization_fails(): void
    {
        $year = 2022;
        $month = 11;
        $day = 22;
        $hour = 15;
        $minute = 29;
        $second = 47;
        $microsecond = 1234457;

        $this->expectException(DateTimeSerializationException::class);

        $this->sut->deserialize("{$year}-{$month}-{$day} F {$hour}:{$minute}:{$second}.{$microsecond}Z");
    }

    public function test_desiralize_throwsDateTimeSerializationException_with_error_data(): void
    {
        $year = 2022;
        $month = 11;
        $day = 22;
        $hour = 15;
        $minute = 29;
        $second = 47;
        $microsecond = 1234457;

        $serializedDateTime = "{$year}-{$month}-{$day} F {$hour}:{$minute}:{$second}.{$microsecond}Z";

        try {
            $this->sut->deserialize("{$year}-{$month}-{$day} F {$hour}:{$minute}:{$second}.{$microsecond}Z");
        } catch (DateTimeSerializationException $e) {
            $this->assertEquals($serializedDateTime, $e->getSerializedDateTime());
            $this->assertNotEmpty($e->getLastDateTimeErrors());
        }
    }
}
