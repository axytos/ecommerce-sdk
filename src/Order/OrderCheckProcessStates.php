<?php

namespace Axytos\ECommerce\Order;

abstract class OrderCheckProcessStates
{
    const UNCHECKED = 'UNCHECKED';
    const CHECKED = 'CHECKED';
    const CONFIRMED = 'CONFIRMED';
    const FAILED = 'FAILED';
}
