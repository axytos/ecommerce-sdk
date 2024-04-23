<?php

namespace Axytos\ECommerce\Tests\Integration\OrderSync;

use PHPUnit\Framework\Attributes\Group;

include_once __DIR__ . '/OrderSyncWorkerIntegratedTestCase.php';

/**
 * @group OrderSync
 */
#[Group('OrderSync')]
class ReportRefundTest extends OrderSyncWorkerIntegratedTestCase
{
    /**
     * @return void
     */
    public function test_reports_refund_for_invoiced_and_refunded_orders()
    {
        $this->executeTestCases([[
            'order' => [

                'hasCreateInvoiceReported' => true,
                'hasBeenInvoiced' => false,

                'hasCancelReported' => false,
                'hasBeenCanceled' => false,

                'hasRefundReported' => false,
                'hasBeenRefunded' => true,

                'hasShippingReported' => false,
                'hasBeenShipped' => false,

                'hasNewTrackingInformation' => false,

                'hasBasketUpdates' => false,
            ],
            'expected' => [
                'reportCancel' => false,
                'reportCreateInvoice' => false,
                'reportRefund' => true,
                'reportShipping' => false,
                'reportTrackingInformation' => false,
                'reportUpdate' => false,
            ]
        ], [
            'order' => [

                'hasCreateInvoiceReported' => true,
                'hasBeenInvoiced' => false,

                'hasCancelReported' => false,
                'hasBeenCanceled' => false,

                'hasRefundReported' => false,
                'hasBeenRefunded' => true,

                'hasShippingReported' => false,
                'hasBeenShipped' => false,

                'hasNewTrackingInformation' => false,

                'hasBasketUpdates' => false,
            ],
            'expected' => [
                'reportCancel' => false,
                'reportCreateInvoice' => false,
                'reportRefund' => true,
                'reportShipping' => false,
                'reportTrackingInformation' => false,
                'reportUpdate' => false,
            ]
        ]]);
    }
    /**
     * @return void
     */
    public function test_does_not_report_refund_for_not_invoiced_orders()
    {
        $this->executeTestCases([[
            'order' => [

                'hasCreateInvoiceReported' => false,
                'hasBeenInvoiced' => false,

                'hasCancelReported' => false,
                'hasBeenCanceled' => false,

                'hasRefundReported' => false,
                'hasBeenRefunded' => true,

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
            ]
        ]]);
    }
}
