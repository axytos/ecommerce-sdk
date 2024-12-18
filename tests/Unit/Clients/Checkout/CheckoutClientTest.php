<?php

namespace Axytos\ECommerce\Tests\Unit\Clients\Checkout;

use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;
use Axytos\ECommerce\Clients\Checkout\CheckoutApiInterface;
use Axytos\ECommerce\Clients\Checkout\CheckoutClient;
use Axytos\ECommerce\Clients\Checkout\CreditCheckAgreementLoadFailedException;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class CheckoutClientTest extends TestCase
{
    const SELECTED_PAYMENTMETHOD_ID = 'SELECTED_PAYMENTMETHOD_ID';
    const CREDIT_CHECK_AGREEMENT_INFO = 'CREDIT_CHECK_AGREEMENT_INFO';

    /** @var CheckoutApiInterface&MockObject */
    private $checkoutApi;

    /** @var PaymentMethodConfigurationInterface&MockObject */
    private $paymentMethodConfiguration;

    /**
     * @var CheckoutClient
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
        $this->checkoutApi = $this->createMock(CheckoutApiInterface::class);
        $this->paymentMethodConfiguration = $this->createMock(PaymentMethodConfigurationInterface::class);

        $this->sut = new CheckoutClient(
            $this->paymentMethodConfiguration,
            $this->checkoutApi
        );
    }

    /**
     * @param string $paymentMethodId
     * @param bool   $isSafe
     * @param bool   $isUnsafe
     *
     * @return void
     */
    private function mockPaymentMethodConfiguration($paymentMethodId, $isSafe, $isUnsafe)
    {
        $paymentMethodId = (string) $paymentMethodId;
        $isSafe = (bool) $isSafe;
        $isUnsafe = (bool) $isUnsafe;
        $this->paymentMethodConfiguration
            ->method('isSafe')
            ->with($paymentMethodId)
            ->willReturn($isSafe)
        ;
        $this->paymentMethodConfiguration
            ->method('isUnsafe')
            ->with($paymentMethodId)
            ->willReturn($isUnsafe)
        ;
    }

    /**
     * @return void
     */
    public function test_must_show_credit_check_agreement_returns_true_if_payment_method_is_safe()
    {
        $this->mockPaymentMethodConfiguration(self::SELECTED_PAYMENTMETHOD_ID, true, false);

        $actual = $this->sut->mustShowCreditCheckAgreement(self::SELECTED_PAYMENTMETHOD_ID);

        $this->assertTrue($actual);
    }

    /**
     * @return void
     */
    public function test_must_show_credit_check_agreement_returns_true_if_payment_method_is_unsafe()
    {
        $this->mockPaymentMethodConfiguration(self::SELECTED_PAYMENTMETHOD_ID, false, true);

        $actual = $this->sut->mustShowCreditCheckAgreement(self::SELECTED_PAYMENTMETHOD_ID);

        $this->assertTrue($actual);
    }

    /**
     * @return void
     */
    public function test_must_show_credit_check_agreement_returns_false_if_payment_method_is_neither_safe_nor_unsafe()
    {
        $this->mockPaymentMethodConfiguration(self::SELECTED_PAYMENTMETHOD_ID, false, false);

        $actual = $this->sut->mustShowCreditCheckAgreement(self::SELECTED_PAYMENTMETHOD_ID);

        $this->assertFalse($actual);
    }

    /**
     * @return void
     */
    public function test_get_credit_check_agreement_info_returns_from_checkout_api()
    {
        $this->checkoutApi
            ->method('getCreditCheckAgreementText')
            ->willReturn(self::CREDIT_CHECK_AGREEMENT_INFO)
        ;

        $actual = $this->sut->getCreditCheckAgreementInfo();

        $this->assertSame(self::CREDIT_CHECK_AGREEMENT_INFO, $actual);
    }

    /**
     * @return void
     */
    public function test_get_credit_check_agreement_info_throws_credit_check_agreement_load_failed_exception()
    {
        $this->checkoutApi
            ->method('getCreditCheckAgreementText')
            ->willThrowException(new \Exception())
        ;

        $this->expectException(CreditCheckAgreementLoadFailedException::class);

        $this->sut->getCreditCheckAgreementInfo();
    }
}
