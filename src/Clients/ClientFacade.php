<?php

namespace Axytos\ECommerce\Clients;

use Throwable;
use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClientInterface;

class ClientFacade implements CheckoutClientInterface, CredentialValidationClientInterface, ErrorReportingClientInterface, InvoiceClientInterface
{
    /**
     * @var \Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface
     */
    private $checkoutClient;
    /**
     * @var \Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClientInterface
     */
    private $CredentialValidationClient;
    /**
     * @var \Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface
     */
    private $errorReportingClient;
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface
     */
    private $invoiceClient;

    public function __construct(Container $container)
    {
        $this->checkoutClient = $container->get(CheckoutClientInterface::class);
        $this->CredentialValidationClient = $container->get(CredentialValidationClientInterface::class);
        $this->errorReportingClient = $container->get(ErrorReportingClientInterface::class);
        $this->invoiceClient = $container->get(InvoiceClientInterface::class);
    }

    /**
     * @param string $selectedPaymentMethodId
     * @return bool
     */
    public function mustShowCreditCheckAgreement($selectedPaymentMethodId)
    {
        return $this->checkoutClient->mustShowCreditCheckAgreement($selectedPaymentMethodId);
    }

    /**
     * @return string
     */
    public function getCreditCheckAgreementInfo()
    {
        return $this->checkoutClient->getCreditCheckAgreementInfo();
    }

    /**
     * @return bool
     */
    public function validateApiKey()
    {
        return $this->CredentialValidationClient->validateApiKey();
    }

    /**
     * @param \Throwable $throwable
     * @return void
     */
    public function reportError($throwable)
    {
        $this->errorReportingClient->reportError($throwable);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return string
     */
    public function precheck($orderContext)
    {
        return $this->invoiceClient->precheck($orderContext);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function confirmOrder($orderContext)
    {
        $this->invoiceClient->confirmOrder($orderContext);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function cancelOrder($orderContext)
    {
        $this->invoiceClient->cancelOrder($orderContext);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function uncancelOrder($orderContext)
    {
        $this->invoiceClient->uncancelOrder($orderContext);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function createInvoice($orderContext)
    {
        $this->invoiceClient->createInvoice($orderContext);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function reportShipping($orderContext)
    {
        $this->invoiceClient->reportShipping($orderContext);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function trackingInformation($orderContext)
    {
        $this->invoiceClient->trackingInformation($orderContext);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function refund($orderContext)
    {
        $this->invoiceClient->refund($orderContext);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function returnOrder($orderContext)
    {
        $this->invoiceClient->returnOrder($orderContext);
    }

    /**
     * @param string $paymentId
     * @return \Axytos\ECommerce\Clients\Invoice\InvoiceOrderPaymentUpdate
     */
    public function getInvoiceOrderPaymentUpdate($paymentId)
    {
        return $this->invoiceClient->getInvoiceOrderPaymentUpdate($paymentId);
    }

    /**
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return void
     */
    public function updateOrder($orderContext)
    {
        $this->invoiceClient->updateOrder($orderContext);
    }

    /**
     *
     * @param \Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface $orderContext
     * @return bool
     */
    public function hasBeenPaid($orderContext)
    {
        return $this->invoiceClient->hasBeenPaid($orderContext);
    }
}
