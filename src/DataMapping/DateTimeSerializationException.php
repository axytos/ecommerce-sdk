<?php

namespace Axytos\ECommerce\DataMapping;

use Exception;

class DateTimeSerializationException extends Exception
{
    /**
     * @var string
     */
    private $serializedDateTime;
    /**
     * @var mixed[]
     */
    private $lastDateTimeErrors;

    /**
     * @param string $serializedDateTime
     */
    public function __construct($serializedDateTime, array $lastDateTimeErrors)
    {
        $serializedDateTime = (string) $serializedDateTime;
        $this->serializedDateTime = $serializedDateTime;
        $this->lastDateTimeErrors = $lastDateTimeErrors;

        parent::__construct("Cannot convert '$serializedDateTime' to DateTime object!");
    }

    /**
     * @return string
     */
    public function getSerializedDateTime()
    {
        return $this->serializedDateTime;
    }

    /**
     * @return mixed[]
     */
    public function getLastDateTimeErrors()
    {
        return $this->lastDateTimeErrors;
    }
}
