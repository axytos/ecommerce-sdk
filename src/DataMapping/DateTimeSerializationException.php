<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataMapping;

use Exception;

class DateTimeSerializationException extends Exception
{
    private string $serializedDateTime;
    private array $lastDateTimeErrors;
    
    public function __construct(string $serializedDateTime, array $lastDateTimeErrors)
    {
        $this->serializedDateTime = $serializedDateTime;
        $this->lastDateTimeErrors = $lastDateTimeErrors;
        
        parent::__construct("Cannot convert '$serializedDateTime' to DateTime object!");
    }

    public function getSerializedDateTime(): string
    {
        return $this->serializedDateTime;
    }

    public function getLastDateTimeErrors(): array
    {
        return $this->lastDateTimeErrors;
    }
}