<?php

namespace Axytos\ECommerce\Tests\Unit\Clients;

use Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface;
use Axytos\ECommerce\Clients\ClientFacade;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClientInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderPaymentUpdate;
use Axytos\ECommerce\DependencyInjection\Container;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ClientFacadeTest extends TestCase
{
    /** @var CheckoutClientInterface&MockObject */
    private $checkoutClient;

    /** @var CredentialValidationClientInterface&MockObject */
    private $CredentialValidationClient;

    /** @var ErrorReportingClientInterface&MockObject */
    private $errorReportingClient;

    /** @var InvoiceClientInterface&MockObject */
    private $invoiceClient;

    /**
     * @var ClientFacade
     */
    private $sut;

    /**
     * @return void
     *
     * @before
     */
    #[Before]
    public function beforeEach()
    {
        $this->checkoutClient = $this->createMock(CheckoutClientInterface::class);
        $this->CredentialValidationClient = $this->createMock(CredentialValidationClientInterface::class);
        $this->errorReportingClient = $this->createMock(ErrorReportingClientInterface::class);
        $this->invoiceClient = $this->createMock(InvoiceClientInterface::class);

        $container = $this->createContainerMock([
            CheckoutClientInterface::class => $this->checkoutClient,
            CredentialValidationClientInterface::class => $this->CredentialValidationClient,
            ErrorReportingClientInterface::class => $this->errorReportingClient,
            InvoiceClientInterface::class => $this->invoiceClient,
        ]);

        $this->sut = new ClientFacade($container);
    }

    /**
     * @param array<string,mixed> $containerConfig
     *
     * @return Container
     */
    private function createContainerMock(array $containerConfig)
    {
        $returnValueMap = [];
        foreach ($containerConfig as $key => $value) {
            array_push($returnValueMap, [$key, $value]);
        }

        /** @var Container&MockObject */
        $container = $this->createMock(Container::class);
        $container->method('get')->willReturnMap($returnValueMap);

        return $container;
    }

    /**
     * @return void
     */
    public function test_must_show_credit_check_agreement_delegates_to_checkout_client()
    {
        $paymentMethodId = 'paymentMethodId';
        $expected = true;

        $this->checkoutClient
            ->method('mustShowCreditCheckAgreement')
            ->with($paymentMethodId)
            ->willReturn($expected)
        ;

        $actual = $this->sut->mustShowCreditCheckAgreement($paymentMethodId);

        $this->assertSame($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_get_credit_check_agreement_info_delegates_to_checkout_client()
    {
        $expected = 'credit check agreement';

        $this->checkoutClient
            ->method('getCreditCheckAgreementInfo')
            ->willReturn($expected)
        ;

        $actual = $this->sut->getCreditCheckAgreementInfo();

        $this->assertSame($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_validate_api_key_delegates_to_service_availibility_client()
    {
        $expected = true;

        $this->CredentialValidationClient
            ->method('validateApiKey')
            ->willReturn($expected)
        ;

        $actual = $this->sut->validateApiKey();

        $this->assertSame($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_report_error_delegates_to_error_reporting_client()
    {
        $throwable = new \Exception();

        $this->errorReportingClient
            ->expects($this->once())
            ->method('reportError')
            ->with($throwable)
        ;

        $this->sut->reportError($throwable);
    }

    /**
     * @return void
     */
    public function test_precheck_delegates_to_invoice_client()
    {
        $orderContext = $this->createMock(InvoiceOrderContextInterface::class);

        $shopAction = 'shopAction';

        $this->invoiceClient
            ->expects($this->once())
            ->method('precheck')
            ->with($orderContext)
            ->willReturn($shopAction)
        ;

        $actual = $this->sut->precheck($orderContext);

        $this->assertSame($shopAction, $actual);
    }

    /**
     * @return void
     */
    public function test_confirm_order_delegates_to_invoice_client()
    {
        $orderContext = $this->createMock(InvoiceOrderContextInterface::class);

        $this->invoiceClient
            ->expects($this->once())
            ->method('confirmOrder')
            ->with($orderContext)
        ;

        $this->sut->confirmOrder($orderContext);
    }

    /**
     * @return void
     */
    public function test_cancel_order_delegates_to_invoice_client()
    {
        $orderContext = $this->createMock(InvoiceOrderContextInterface::class);

        $this->invoiceClient
            ->expects($this->once())
            ->method('cancelOrder')
            ->with($orderContext)
        ;

        $this->sut->cancelOrder($orderContext);
    }

    /**
     * @return void
     */
    public function test_create_invoice_delegates_to_invoice_client()
    {
        $orderContext = $this->createMock(InvoiceOrderContextInterface::class);

        $this->invoiceClient
            ->expects($this->once())
            ->method('createInvoice')
            ->with($orderContext)
        ;

        $this->sut->createInvoice($orderContext);
    }

    /**
     * @return void
     */
    public function test_report_shipping_delegates_to_invoice_client()
    {
        $orderContext = $this->createMock(InvoiceOrderContextInterface::class);

        $this->invoiceClient
            ->expects($this->once())
            ->method('reportShipping')
            ->with($orderContext)
        ;

        $this->sut->reportShipping($orderContext);
    }

    /**
     * @return void
     */
    public function test_return_delegates_to_invoice_client()
    {
        $orderContext = $this->createMock(InvoiceOrderContextInterface::class);

        $this->invoiceClient
            ->expects($this->once())
            ->method('returnOrder')
            ->with($orderContext)
        ;

        $this->sut->returnOrder($orderContext);
    }

    /**
     * @return void
     */
    public function test_refund_delegates_to_invoice_client()
    {
        $orderContext = $this->createMock(InvoiceOrderContextInterface::class);

        $this->invoiceClient
            ->expects($this->once())
            ->method('refund')
            ->with($orderContext)
        ;

        $this->sut->refund($orderContext);
    }

    /**
     * @return void
     */
    public function test_get_invoice_order_payment_update_delegates_to_invoice_client()
    {
        $paymentId = 'paymentId';

        $paymentUpdate = $this->createMock(InvoiceOrderPaymentUpdate::class);

        $this->invoiceClient
            ->expects($this->once())
            ->method('getInvoiceOrderPaymentUpdate')
            ->with($paymentId)
            ->willReturn($paymentUpdate)
        ;

        $actual = $this->sut->getInvoiceOrderPaymentUpdate($paymentId);

        $this->assertSame($paymentUpdate, $actual);
    }

    /**
     * @return void
     */
    public function test_update_order_delegates_to_invoice_client()
    {
        $orderContext = $this->createMock(InvoiceOrderContextInterface::class);

        $this->invoiceClient
            ->expects($this->once())
            ->method('updateOrder')
            ->with($orderContext)
        ;

        $this->sut->updateOrder($orderContext);
    }
}
