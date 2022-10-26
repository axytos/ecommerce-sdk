<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\Clients\Checkout;

use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;
use Axytos\ECommerce\Clients\Checkout\CheckoutApiInterface;
use Axytos\ECommerce\Clients\Checkout\CheckoutClient;
use Axytos\ECommerce\Clients\Checkout\CreditCheckAgreementLoadFailedException;
use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CheckoutClientTest extends TestCase
{
    private const SELECTED_PAYMENTMETHOD_ID = 'SELECTED_PAYMENTMETHOD_ID';
    private const CREDIT_CHECK_AGREEMENT_INFO = 'CREDIT_CHECK_AGREEMENT_INFO';

    /** @var CheckoutApiInterface&MockObject */
    private CheckoutApiInterface $checkoutApi;

    /** @var PaymentMethodConfigurationInterface&MockObject */
    private PaymentMethodConfigurationInterface $paymentMethodConfiguration;

    private CheckoutClient $sut;

    public function setUp(): void
    {
        $this->checkoutApi = $this->createMock(CheckoutApiInterface::class);
        $this->paymentMethodConfiguration = $this->createMock(PaymentMethodConfigurationInterface::class);

        $this->sut = new CheckoutClient(
            $this->paymentMethodConfiguration,
            $this->checkoutApi
        );
    }

    private function mockPaymentMethodConfiguration(string $paymentMethodId, bool $isSafe, bool $isUnsafe): void
    {
        $this->paymentMethodConfiguration
            ->method('isSafe')
            ->with($paymentMethodId)
            ->willReturn($isSafe);
        $this->paymentMethodConfiguration
            ->method('isUnsafe')
            ->with($paymentMethodId)
            ->willReturn($isUnsafe);
    }

    public function test_mustShowCreditCheckAgreement_returns_true_if_payment_method_is_safe(): void
    {
        $this->mockPaymentMethodConfiguration(self::SELECTED_PAYMENTMETHOD_ID, true, false);

        $actual = $this->sut->mustShowCreditCheckAgreement(self::SELECTED_PAYMENTMETHOD_ID);

        $this->assertTrue($actual);
    }

    public function test_mustShowCreditCheckAgreement_returns_true_if_payment_method_is_unsafe(): void
    {
        $this->mockPaymentMethodConfiguration(self::SELECTED_PAYMENTMETHOD_ID, false, true);

        $actual = $this->sut->mustShowCreditCheckAgreement(self::SELECTED_PAYMENTMETHOD_ID);

        $this->assertTrue($actual);
    }

    public function test_mustShowCreditCheckAgreement_returns_false_if_payment_method_is_neither_safe_nor_unsafe(): void
    {
        $this->mockPaymentMethodConfiguration(self::SELECTED_PAYMENTMETHOD_ID, false, false);

        $actual = $this->sut->mustShowCreditCheckAgreement(self::SELECTED_PAYMENTMETHOD_ID);

        $this->assertFalse($actual);
    }

    public function test_getCreditCheckAgreementInfo_returns_from_checkout_api(): void
    {
        $this->checkoutApi
            ->method('getCreditCheckAgreementText')
            ->willReturn(self::CREDIT_CHECK_AGREEMENT_INFO);

        $actual = $this->sut->getCreditCheckAgreementInfo();

        $this->assertSame(self::CREDIT_CHECK_AGREEMENT_INFO, $actual);
    }

    public function test_getCreditCheckAgreementInfo_throws_CreditCheckAgreementLoadFailedException(): void
    {
        $this->checkoutApi
            ->method('getCreditCheckAgreementText')
            ->willThrowException(new Exception());

        $this->expectException(CreditCheckAgreementLoadFailedException::class);

        $this->sut->getCreditCheckAgreementInfo();
    }
}
