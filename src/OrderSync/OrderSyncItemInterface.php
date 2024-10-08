<?php

namespace Axytos\ECommerce\OrderSync;

interface OrderSyncItemInterface
{
    /**
     * @return void
     */
    public function reportCancel();

    /**
     * @return void
     */
    public function reportCreateInvoice();

    /**
     * @return void
     */
    public function reportRefund();

    /**
     * @return void
     */
    public function reportShipping();

    /**
     * @return void
     */
    public function reportTrackingInformation();

    /**
     * @return void
     */
    public function reportUpdate();

    /**
     * @param OrderSyncCommandInterface $command
     *
     * @return void
     */
    public function execute($command);
}
