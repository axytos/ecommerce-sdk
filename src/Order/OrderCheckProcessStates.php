<?php declare(strict_types=1);

namespace Axytos\ECommerce\Order;

abstract class OrderCheckProcessStates
{
    public const UNCHECKED = 'UNCHECKED';
    public const CHECKED = 'CHECKED';
    public const CONFIRMED = 'CONFIRMED';
    public const FAILED = 'FAILED';
}