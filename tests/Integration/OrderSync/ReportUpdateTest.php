<?php

namespace Axytos\ECommerce\Tests\Integration\OrderSync;

use PHPUnit\Framework\Attributes\Group;

include_once __DIR__ . '/OrderSyncWorkerIntegratedTestCase.php';

/**
 * @group OrderSync
 *
 * @internal
 */
#[Group('OrderSync')]
class ReportUpdateTest extends OrderSyncWorkerIntegratedTestCase
{
    /**
     * @return void
     */
    public function test_reports_update_for_changed_orders()
    {
        $this->executeTestCases([[
            'order' => [
                'hasCreateInvoiceReported' => false,
                'hasBeenInvoiced' => false,

                'hasCancelReported' => false,
                'hasBeenCanceled' => false,

                'hasRefundReported' => false,
                'hasBeenRefunded' => false,

                'hasShippingReported' => false,
                'hasBeenShipped' => false,

                'hasNewTrackingInformation' => false,

                'hasBasketUpdates' => true,
            ],
            'expected' => [
                'reportCancel' => false,
                'reportCreateInvoice' => false,
                'reportRefund' => false,
                'reportShipping' => false,
                'reportTrackingInformation' => false,
                'reportUpdate' => true,
            ],
        ]]);
    }

    /**
     * @return void
     */
    public function test_does_not_report_update_for_invoiced_orders()
    {
        $this->executeTestCases([[
            'order' => [
                'hasCreateInvoiceReported' => true,
                'hasBeenInvoiced' => false,

                'hasCancelReported' => false,
                'hasBeenCanceled' => false,

                'hasRefundReported' => false,
                'hasBeenRefunded' => false,

                'hasShippingReported' => false,
                'hasBeenShipped' => false,

                'hasNewTrackingInformation' => false,

                'hasBasketUpdates' => true,
            ],
            'expected' => [
                'reportCancel' => false,
                'reportCreateInvoice' => false,
                'reportRefund' => false,
                'reportShipping' => false,
                'reportTrackingInformation' => false,
                'reportUpdate' => false,
            ],
        ]]);
    }

    /**
     * @return void
     */
    public function test_does_not_report_update_for_orders_without_basket_updates()
    {
        $this->executeTestCases([[
            'order' => [
                'hasCreateInvoiceReported' => false,
                'hasBeenInvoiced' => false,

                'hasCancelReported' => false,
                'hasBeenCanceled' => false,

                'hasRefundReported' => false,
                'hasBeenRefunded' => false,

                'hasShippingReported' => false,
                'hasBeenShipped' => false,

                'hasNewTrackingInformation' => false,

                'hasBasketUpdates' => false,
            ],
            'expected' => [
                'reportCancel' => false,
                'reportCreateInvoice' => false,
                'reportRefund' => false,
                'reportShipping' => false,
                'reportTrackingInformation' => false,
                'reportUpdate' => false,
            ],
        ]]);
    }
}
