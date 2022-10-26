<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\Clients\PaymentControl;

use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlApiInterface;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderData;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlClient;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlAction;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlCacheInterface;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlCheckFailedException;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlConfirmFailedException;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderDataHashCalculator;
use Axytos\ECommerce\DataTransferObjects\CheckDecisions;
use Axytos\ECommerce\DataTransferObjects\PaymentTypeSecurities;
use Axytos\ECommerce\DataTransferObjects\PaymentControlBasketDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto;
use Axytos\ECommerce\DataTransferObjects\TransactionMetadataDto;
use DateInterval;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PaymentControlClientTest extends TestCase
{
    private const PAYMENT_METHOD_ID = 'PAYMENT_METHOD_ID';

    /** @var PaymentControlApiInterface&MockObject */
    private PaymentControlApiInterface $paymentControlApi;

    /** @var PaymentMethodConfigurationInterface&MockObject */
    private PaymentMethodConfigurationInterface $paymentMethodConfiguration;

    /** @var PaymentControlOrderDataHashCalculator&MockObject */
    private PaymentControlOrderDataHashCalculator $paymentControlOrderDataHashCalculator;

    /** @var PaymentControlCacheInterface&MockObject */
    private PaymentControlCacheInterface $paymentControlCache;

    private PaymentControlOrderData $data;

    private PaymentControlClient $sut;

    /** @var PaymentControlCheckResponseDto&MockObject */
    private PaymentControlCheckResponseDto $checkResponse;

    public function setUp(): void
    {
        $this->paymentControlApi = $this->createMock(PaymentControlApiInterface::class);
        $this->paymentMethodConfiguration = $this->createMock(PaymentMethodConfigurationInterface::class);
        $this->paymentControlOrderDataHashCalculator = $this->createMock(PaymentControlOrderDataHashCalculator::class);
        $this->paymentControlCache = $this->createMock(PaymentControlCacheInterface::class);

        $this->sut = new PaymentControlClient(
            $this->paymentControlApi,
            $this->paymentMethodConfiguration,
            $this->paymentControlOrderDataHashCalculator,
        );

        $this->checkResponse = $this->createMock(PaymentControlCheckResponseDto::class);

        $this->setUpCheckResponse();
        $this->setUpPaymetControlOrderData();
    }

    private function setUpCheckResponse(): void
    {
        $this->checkResponse->transactionMetadata = $this->createMock(TransactionMetadataDto::class);
        $this->checkResponse->transactionMetadata->transactionTimestamp = new DateTimeImmutable();
        $this->checkResponse->transactionMetadata->transactionExpirationTimestamp = new DateTimeImmutable();
        $this->paymentControlApi
            ->method('paymentControlCheck')
            ->willReturn($this->checkResponse);
        $this->paymentControlCache
            ->method('getCheckResponse')
            ->willReturn($this->checkResponse);
    }

    private function setUpPaymetControlOrderData(): void
    {
        $this->data = new PaymentControlOrderData();
        $this->data->paymentMethodId = self::PAYMENT_METHOD_ID;
        $this->data->personalData = $this->createMock(CustomerDataDto::class);
        $this->data->invoiceAddress = $this->createMock(InvoiceAddressDto::class);
        $this->data->deliveryAddress = $this->createMock(DeliveryAddressDto::class);
        $this->data->basket = $this->createMock(PaymentControlBasketDto::class);
    }

    private function setUpPaymentTypeSecurity(?string $paymentTypeSecurity): void
    {
        if ($paymentTypeSecurity === PaymentTypeSecurities::SAFE) {
            $this->paymentMethodConfiguration
                ->method('isSafe')
                ->with(self::PAYMENT_METHOD_ID)
                ->willReturn(true);
        }

        if ($paymentTypeSecurity === PaymentTypeSecurities::UNSAFE) {
            $this->paymentMethodConfiguration
                ->method('isUnsafe')
                ->with(self::PAYMENT_METHOD_ID)
                ->willReturn(true);
        }
    }

    private function setUpDecision(string $decision): void
    {
        $this->checkResponse->decision = $decision;
    }

    /**
     * @dataProvider dataProvider_test_check_returns_correct_payment_control_action
     */
    public function test_check_returns_correct_payment_control_action(?string $paymentTypeSecurity, string $decision, string $expectedAction): void
    {
        $this->setUpPaymentTypeSecurity($paymentTypeSecurity);
        $this->setUpDecision($decision);

        $actual = $this->sut->check($this->data, $this->paymentControlCache);

        $this->assertEquals($expectedAction, $actual);
    }

    public function dataProvider_test_check_returns_correct_payment_control_action(): array
    {
        return [
            [null, CheckDecisions::SAFE, PaymentControlAction::COMPLETE_ORDER],
            [null, CheckDecisions::UNSAFE, PaymentControlAction::COMPLETE_ORDER],
            [null, CheckDecisions::REJECT, PaymentControlAction::COMPLETE_ORDER],

            [PaymentTypeSecurities::SAFE, CheckDecisions::SAFE, PaymentControlAction::COMPLETE_ORDER],
            [PaymentTypeSecurities::SAFE, CheckDecisions::UNSAFE, PaymentControlAction::COMPLETE_ORDER],
            [PaymentTypeSecurities::SAFE, CheckDecisions::REJECT, PaymentControlAction::CANCEL_ORDER],

            [PaymentTypeSecurities::UNSAFE, CheckDecisions::SAFE, PaymentControlAction::CHANGE_PAYMENT_METHOD],
            [PaymentTypeSecurities::UNSAFE, CheckDecisions::UNSAFE, PaymentControlAction::COMPLETE_ORDER],
            [PaymentTypeSecurities::UNSAFE, CheckDecisions::REJECT, PaymentControlAction::CANCEL_ORDER],
        ];
    }

    public function test_check_sends_request_mode_single_step(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlCheckRequestDto $request) {
            return $request->requestMode === 'SingleStep';
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlCheck')
            ->with($matcher);

        $this->sut->check($this->data, $this->paymentControlCache);
    }

    public function test_check_sends_proof_of_interest_AAE(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlCheckRequestDto $request) {
            return $request->proofOfInterest === 'AAE';
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlCheck')
            ->with($matcher);

        $this->sut->check($this->data, $this->paymentControlCache);
    }

    /**
     * @dataProvider dataProvider_test_check_sends_correct_payment_type_security
     */
    public function test_check_sends_correct_payment_type_security(string $paymentTypeSecurity): void
    {
        $this->setUpPaymentTypeSecurity($paymentTypeSecurity);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlCheckRequestDto $request) use ($paymentTypeSecurity) {
            return $request->paymentTypeSecurity === $paymentTypeSecurity;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlCheck')
            ->with($matcher);

        $this->sut->check($this->data, $this->paymentControlCache);
    }

    public function dataProvider_test_check_sends_correct_payment_type_security(): array
    {
        return [
            [PaymentTypeSecurities::SAFE],
            [PaymentTypeSecurities::UNSAFE],
        ];
    }

    public function test_check_sends_personal_data(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlCheckRequestDto $request) {
            return $request->personalData === $this->data->personalData;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlCheck')
            ->with($matcher);

        $this->sut->check($this->data, $this->paymentControlCache);
    }

    public function test_check_sends_invoice_address(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlCheckRequestDto $request) {
            return $request->invoiceAddress === $this->data->invoiceAddress;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlCheck')
            ->with($matcher);

        $this->sut->check($this->data, $this->paymentControlCache);
    }

    public function test_check_sends_delivery_address(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlCheckRequestDto $request) {
            return $request->deliveryAddress === $this->data->deliveryAddress;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlCheck')
            ->with($matcher);

        $this->sut->check($this->data, $this->paymentControlCache);
    }

    public function test_check_sends_basket(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlCheckRequestDto $request) {
            return $request->basket === $this->data->basket;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlCheck')
            ->with($matcher);

        $this->sut->check($this->data, $this->paymentControlCache);
    }

    public function test_check_throws_PaymentControlCheckFailedException(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $this->paymentControlApi
            ->method('paymentControlCheck')
            ->willThrowException(new Exception());

        $this->expectException(PaymentControlCheckFailedException::class);
        $this->sut->check($this->data, $this->paymentControlCache);
    }

    public function test_check_saves_response_in_chache(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $this->paymentControlCache
            ->expects($this->once())
            ->method('setCheckResponse')
            ->with($this->checkResponse);

        $this->sut->check($this->data, $this->paymentControlCache);
    }

    //=================================================================================
    // confirm

    /**
     * @dataProvider dataProvider_test_confirm_sends_correct_payment_type_security
     */
    public function test_confirm_sends_correct_payment_type_security(string $paymentTypeSecurity): void
    {
        $this->setUpPaymentTypeSecurity($paymentTypeSecurity);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlConfirmRequestDto $request) use ($paymentTypeSecurity) {
            return $request->paymentTypeSecurity === $paymentTypeSecurity;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlConfirm')
            ->with($matcher);

        $this->sut->confirm($this->data, $this->paymentControlCache);
    }

    public function dataProvider_test_confirm_sends_correct_payment_type_security(): array
    {
        return [
            [PaymentTypeSecurities::SAFE],
            [PaymentTypeSecurities::UNSAFE],
        ];
    }

    public function test_confirm_sends_personal_data(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlConfirmRequestDto $request) {
            return $request->personalData === $this->data->personalData;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlConfirm')
            ->with($matcher);

        $this->sut->confirm($this->data, $this->paymentControlCache);
    }

    public function test_confirm_sends_invoice_address(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlConfirmRequestDto $request) {
            return $request->invoiceAddress === $this->data->invoiceAddress;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlConfirm')
            ->with($matcher);

        $this->sut->confirm($this->data, $this->paymentControlCache);
    }

    public function test_confirm_sends_delivery_address(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlConfirmRequestDto $request) {
            return $request->deliveryAddress === $this->data->deliveryAddress;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlConfirm')
            ->with($matcher);

        $this->sut->confirm($this->data, $this->paymentControlCache);
    }

    public function test_confirm_sends_basket(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlConfirmRequestDto $request) {
            return $request->basket === $this->data->basket;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlConfirm')
            ->with($matcher);

        $this->sut->confirm($this->data, $this->paymentControlCache);
    }

    public function test_confirm_sends_response(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $matcher = $this->callback(function (PaymentControlConfirmRequestDto $request) {
            return $request->paymentControlResponse === $this->checkResponse;
        });

        $this->paymentControlApi
            ->expects($this->once())
            ->method('paymentControlConfirm')
            ->with($matcher);

        $this->sut->confirm($this->data, $this->paymentControlCache);
    }

    public function test_confirm_throws_PaymentControlChonfirmFailedException(): void
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $this->paymentControlApi
            ->method('paymentControlConfirm')
            ->willThrowException(new Exception());

        $this->expectException(PaymentControlConfirmFailedException::class);
        $this->sut->confirm($this->data, $this->paymentControlCache);
    }

    public function test_check_returns_COMPLETE_ORDER_if_request_matches_previous_request_and_only_paymentMethod_is_changed(): void
    {
        $hash = 'hash';
        $now = new DateTimeImmutable();
        $fiveMinutes = new DateInterval('PT5M');
        $transactionExpirationTimestamp = $now->add($fiveMinutes);

        $this->checkResponse->transactionMetadata->transactionExpirationTimestamp = $transactionExpirationTimestamp;

        $this->paymentMethodConfiguration
            ->method('isSafe')
            ->with(self::PAYMENT_METHOD_ID)
            ->willReturn(true);

        $this->paymentControlOrderDataHashCalculator
            ->method('computeOrderDataHash')
            ->with($this->data)
            ->willReturn($hash);

        $this->paymentControlCache
            ->method('getCheckResponse')
            ->willReturn($this->checkResponse);

        $this->paymentControlCache
            ->method('getCheckRequestHash')
            ->willReturn($hash);

        $actual = $this->sut->check($this->data, $this->paymentControlCache);

        $expected = PaymentControlAction::COMPLETE_ORDER;

        $this->assertEquals($expected, $actual);
    }
}
