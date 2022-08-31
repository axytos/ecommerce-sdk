<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients;

use Throwable;
use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderData;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlCacheInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlClientInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderPaymentUpdate;

class ClientFacade implements CheckoutClientInterface, PaymentControlClientInterface, CredentialValidationClientInterface, ErrorReportingClientInterface, InvoiceClientInterface
{
    private CheckoutClientInterface $checkoutClient;
    private CredentialValidationClientInterface $CredentialValidationClient;
    private PaymentControlClientInterface $paymentControlClient;
    private ErrorReportingClientInterface $errorReportingClient;
    private InvoiceClientInterface $invoiceClient;

    public function __construct(Container $container)
    {
        $this->checkoutClient = $container->get(CheckoutClientInterface::class);
        $this->CredentialValidationClient = $container->get(CredentialValidationClientInterface::class);
        $this->paymentControlClient = $container->get(PaymentControlClientInterface::class);
        $this->errorReportingClient = $container->get(ErrorReportingClientInterface::class);
        $this->invoiceClient = $container->get(InvoiceClientInterface::class);
    }

    public function mustShowCreditCheckAgreement(string $selectedPaymentMethodId): bool
    {
        return $this->checkoutClient->mustShowCreditCheckAgreement($selectedPaymentMethodId);
    }

    public function getCreditCheckAgreementInfo(): string
    {
        return $this->checkoutClient->getCreditCheckAgreementInfo();
    }

    public function validateApiKey(): bool
    {
        return $this->CredentialValidationClient->validateApiKey();
    }

    public function check(PaymentControlOrderData $data, PaymentControlCacheInterface $paymentControlCache): string
    {
        return $this->paymentControlClient->check($data, $paymentControlCache);
    }

    public function confirm(PaymentControlOrderData $data, PaymentControlCacheInterface $paymentControlCache): void
    {
        $this->paymentControlClient->confirm($data, $paymentControlCache);
    }

    public function reportError(Throwable $throwable): void
    {
        $this->errorReportingClient->reportError($throwable);
    }

    public function precheck(InvoiceOrderContextInterface $orderContext): string
    {
        return $this->invoiceClient->precheck($orderContext);
    }

    public function confirmOrder(InvoiceOrderContextInterface $orderContext): void
    {
        $this->invoiceClient->confirmOrder($orderContext);
    }

    public function cancelOrder(InvoiceOrderContextInterface $orderContext): void
    {
        $this->invoiceClient->cancelOrder($orderContext);
    }

    public function createInvoice(InvoiceOrderContextInterface $orderContext): void
    {
        $this->invoiceClient->createInvoice($orderContext);
    }

    public function reportShipping(InvoiceOrderContextInterface $orderContext): void
    {
        $this->invoiceClient->reportShipping($orderContext);
    }

    public function refund(InvoiceOrderContextInterface $orderContext): void
    {
        $this->invoiceClient->refund($orderContext);
    }

    public function return(InvoiceOrderContextInterface $orderContext): void
    {
        $this->invoiceClient->return($orderContext);
    }

    public function getInvoiceOrderPaymentUpdate(string $paymentId): InvoiceOrderPaymentUpdate
    {
        return $this->invoiceClient->getInvoiceOrderPaymentUpdate($paymentId);
    }
}
