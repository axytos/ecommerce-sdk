<?php declare(strict_types=1);

namespace Axytos\ECommerce\DataTransferObjects;

abstract class CheckDecisions
{
    public const SAFE = 'S';
    public const UNSAFE = 'U';
    public const REJECT = 'R';
}