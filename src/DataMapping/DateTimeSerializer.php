<?php

namespace Axytos\ECommerce\DataMapping;

use DateTimeImmutable;
use DateTimeInterface;

class DateTimeSerializer
{
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s\Z';

    /**
     * @param \DateTimeInterface $dateTime
     * @return string
     */
    public function serialize($dateTime)
    {
        return $dateTime->format(self::DATE_TIME_FORMAT);
    }

    /**
     * @param string $value
     * @return \DateTimeImmutable
     */
    public function deserialize($value)
    {
        $dateTime = DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $value);

        if ($dateTime === false) {
            if (preg_match('/(\d\d\d\d-\d\d-\d\dT\d\d:\d\d:\d\d)/', $value, $matches) === 1) {

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
