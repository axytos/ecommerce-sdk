<?php

declare(strict_types=1);

namespace Axytos\ECommerce\DataMapping;

use DateTimeImmutable;
use DateTimeInterface;

class DateTimeSerializer
{
    private const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s\Z';

    public function serialize(DateTimeInterface $dateTime): string
    {
        return $dateTime->format(self::DATE_TIME_FORMAT);
    }

    public function deserialize(string $value): DateTimeImmutable
    {
        $dateTime = DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $value);

        if ($dateTime === false) {
            if (preg_match('/(\d\d\d\d-\d\d-\d\dT\d\d:\d\d:\d\d)/', $value, $matches)) {

                /**
                 * @phpstan-ignore-next-line
                 */
                return DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', $matches[0]);
            }

            $dateTimeLastErrors = DateTimeImmutable::getLastErrors();
            $dateTimeLastErrors = is_array($dateTimeLastErrors) ? $dateTimeLastErrors : [];
            throw new DateTimeSerializationException($value, $dateTimeLastErrors);
        }

        return $dateTime;
    }
}
