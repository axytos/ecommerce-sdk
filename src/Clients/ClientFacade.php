<?php

namespace Axytos\ECommerce\Clients;

use Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClientInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\DependencyInjection\Container;

class ClientFacade implements CheckoutClientInterface, CredentialValidationClientInterface, ErrorReportingClientInterface, InvoiceClientInterface
{
    /**
     * @var CheckoutClientInterface
     */
    private $checkoutClient;
    /**
     * @var CredentialValidationClientInterface
     */
    private $CredentialValidationClient;
    /**
     * @var ErrorReportingClientInterface
     */
    private $errorReportingClient;
    /**
     * @var InvoiceClientInterface
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
     *
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
     *
     * @return void
     */
    public function reportError($throwable)
    {
        $this->errorReportingClient->reportError($throwable);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return string
     */
    public function precheck($orderContext)
    {
        return $this->invoiceClient->precheck($orderContext);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function confirmOrder($orderContext)
    {
        $this->invoiceClient->confirmOrder($orderContext);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function cancelOrder($orderContext)
    {
        $this->invoiceClient->cancelOrder($orderContext);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function uncancelOrder($orderContext)
    {
        $this->invoiceClient->uncancelOrder($orderContext);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function createInvoice($orderContext)
    {
        $this->invoiceClient->createInvoice($orderContext);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function reportShipping($orderContext)
    {
        $this->invoiceClient->reportShipping($orderContext);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function trackingInformation($orderContext)
    {
        $this->invoiceClient->trackingInformation($orderContext);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function refund($orderContext)
    {
        $this->invoiceClient->refund($orderContext);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function returnOrder($orderContext)
    {
        $this->invoiceClient->returnOrder($orderContext);
    }

    /**
     * @param string $paymentId
     *
     * @return Invoice\InvoiceOrderPaymentUpdate
     */
    public function getInvoiceOrderPaymentUpdate($paymentId)
    {
        return $this->invoiceClient->getInvoiceOrderPaymentUpdate($paymentId);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return void
     */
    public function updateOrder($orderContext)
    {
        $this->invoiceClient->updateOrder($orderContext);
    }

    /**
     * @param Invoice\InvoiceOrderContextInterface $orderContext
     *
     * @return bool
     */
    public function hasBeenPaid($orderContext)
    {
        return $this->invoiceClient->hasBeenPaid($orderContext);
    }
}
