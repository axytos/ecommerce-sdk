<?php declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

use Shopware\Core\System\SalesChannel\SalesChannelContext;

interface PaymentControlClientInterface
{
    function check(PaymentControlOrderData $data, PaymentControlCacheInterface $paymentControlCache): string;

    function confirm(PaymentControlOrderData $data, PaymentControlCacheInterface $paymentControlCache): void;
}
