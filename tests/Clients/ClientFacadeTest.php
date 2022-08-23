<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Clients;

use Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface;
use Axytos\ECommerce\Clients\ClientFacade;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderData;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlClientInterface;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlAction;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClientInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlCacheInterface;
use Axytos\ECommerce\DependencyInjection\Container;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ClientFacadeTest extends TestCase
{
    /** @var CheckoutClientInterface&MockObject */
    private CheckoutClientInterface $checkoutClient;

    /** @var CredentialValidationClientInterface&MockObject */
    private CredentialValidationClientInterface $CredentialValidationClient;

    /** @var PaymentControlClientInterface&MockObject */
    private PaymentControlClientInterface $paymentControlClient;

    /** @var ErrorReportingClientInterface&MockObject  */
    private ErrorReportingClientInterface $errorReportingClient;

    /** @var InvoiceClientInterface&MockObject  */
    private InvoiceClientInterface $invoiceClient;

    private ClientFacade $sut;

    public function setUp(): void
    {
        $this->checkoutClient = $this->createMock(CheckoutClientInterface::class);
        $this->CredentialValidationClient = $this->createMock(CredentialValidationClientInterface::class);
        $this->paymentControlClient = $this->createMock(PaymentControlClientInterface::class);
        $this->errorReportingClient = $this->createMock(ErrorReportingClientInterface::class);
        $this->invoiceClient = $this->createMock(InvoiceClientInterface::class);

        $container = $this->createContainerMock([
            CheckoutClientInterface::class => $this->checkoutClient,
            CredentialValidationClientInterface::class => $this->CredentialValidationClient,
            PaymentControlClientInterface::class => $this->paymentControlClient,
            ErrorReportingClientInterface::class => $this->errorReportingClient,
            InvoiceClientInterface::class => $this->invoiceClient,
        ]);

        $this->sut = new ClientFacade($container);
    }

    private function createContainerMock(array $containerConfig): Container
    {
        $returnValueMap = [];
        foreach($containerConfig as $key => $value)
        {
            array_push($returnValueMap, [$key, $value]);
        }

        /** @var Container&MockObject */
        $container = $this->createMock(Container::class);
        $container->method('get')->willReturnMap($returnValueMap);
        
        return $container;
    }

    public function test_mustShowCreditCheckAgreement_delegates_to_checkout_client(): void
    {
        $paymentMethodId = 'paymentMethodId';
        $expected = true;

        $this->checkoutClient
            ->method('mustShowCreditCheckAgreement')
            ->with($paymentMethodId)
            ->willReturn($expected);

        $actual = $this->sut->mustShowCreditCheckAgreement($paymentMethodId);

        $this->assertSame($expected, $actual);
    }

    public function test_getCreditCheckAgreementInfo_delegates_to_checkout_client(): void
    {
        $expected = 'credit check agreement';

        $this->checkoutClient
            ->method('getCreditCheckAgreementInfo')
            ->willReturn($expected);

        $actual = $this->sut->getCreditCheckAgreementInfo();

        $this->assertSame($expected, $actual);
    }

    public function test_validateApiKey_delegates_to_service_availibility_client(): void
    {
        $expected = true;

        $this->CredentialValidationClient
            ->method('validateApiKey')
            ->willReturn($expected);

        $actual = $this->sut->validateApiKey();

        $this->assertSame($expected, $actual);
    }

    public function test_check_delegates_to_payment_control_client(): void
    {
        $checkData = $this->createMock(PaymentControlOrderData::class);
        $paymentControlCache = $this->createMock(PaymentControlCacheInterface::class);
        $expected = PaymentControlAction::CANCEL_ORDER;

        $this->paymentControlClient
            ->method('check')
            ->with($checkData, $paymentControlCache)
            ->willReturn($expected);

        $actual = $this->sut->check($checkData, $paymentControlCache);

        $this->assertSame($expected, $actual);
    }
}
