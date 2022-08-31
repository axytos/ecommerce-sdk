<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

interface PaymentControlClientInterface
{
    public function check(PaymentControlOrderData $data, PaymentControlCacheInterface $paymentControlCache): string;

    public function confirm(PaymentControlOrderData $data, PaymentControlCacheInterface $paymentControlCache): void;
}
