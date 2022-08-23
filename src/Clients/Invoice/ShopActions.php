<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\Invoice;

abstract class ShopActions
{
    public const COMPLETE_ORDER = 'COMPLETE_ORDER';
    public const CHANGE_PAYMENT_METHOD = 'CHANGE_PAYMENT_METHOD';
}