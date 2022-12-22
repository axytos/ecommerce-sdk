<?php

namespace Axytos\ECommerce\Tests\Unit\DataMapping;

use Axytos\ECommerce\DataMapping\DateTimeSerializationException;
use Axytos\ECommerce\DataMapping\DateTimeSerializer;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class DateTimeSerializerTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\DataMapping\DateTimeSerializer
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->sut = new DateTimeSerializer();
    }

    /**
     * @return void
     */
    public function test_serialize()
    {
        $year = 2022;
        $month = 11;
        $day = 22;
        $hour = 15;
        $minute = 29;
        $second = 47;

        $dateTime = new DateTime();
        $dateTime->setDate($year, $month, $day);
        $dateTime->setTime($hour, $minute, $second);

        $actual = $this->sut->serialize($dateTime);

        $this->assertEquals("{$year}-{$month}-{$day}T{$hour}:{$minute}:{$second}Z", $actual);
    }

    /**
     * @return void
     */
    public function test_deserialize()
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

    /**
     * @return void
     */
    public function test_deserialize_with_seven_microsecond_digits()
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
        $dateTime->setTime($hour, $minute, $second);

        $actual = $this->sut->deserialize("{$year}-{$month}-{$day}T{$hour}:{$minute}:{$second}.{$microsecond}Z");

        $this->assertEquals($dateTime, $actual);
    }

    /**
     * @return void
     */
    public function test_deserialize_throws_DateTimeSerializationException_if_deserialization_fails()
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

    /**
     * @return void
     */
    public function test_desiralize_throwsDateTimeSerializationException_with_error_data()
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
