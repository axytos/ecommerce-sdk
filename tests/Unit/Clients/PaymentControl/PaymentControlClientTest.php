<?php

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
    const PAYMENT_METHOD_ID = 'PAYMENT_METHOD_ID';

    /** @var PaymentControlApiInterface&MockObject */
    private $paymentControlApi;

    /** @var PaymentMethodConfigurationInterface&MockObject */
    private $paymentMethodConfiguration;

    /** @var PaymentControlOrderDataHashCalculator&MockObject */
    private $paymentControlOrderDataHashCalculator;

    /** @var PaymentControlCacheInterface&MockObject */
    private $paymentControlCache;

    /**
     * @var \Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderData
     */
    private $data;

    /**
     * @var \Axytos\ECommerce\Clients\PaymentControl\PaymentControlClient
     */
    private $sut;

    /** @var PaymentControlCheckResponseDto&MockObject */
    private $checkResponse;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->paymentControlApi = $this->createMock(PaymentControlApiInterface::class);
        $this->paymentMethodConfiguration = $this->createMock(PaymentMethodConfigurationInterface::class);
        $this->paymentControlOrderDataHashCalculator = $this->createMock(PaymentControlOrderDataHashCalculator::class);
        $this->paymentControlCache = $this->createMock(PaymentControlCacheInterface::class);

        $this->sut = new PaymentControlClient($this->paymentControlApi, $this->paymentMethodConfiguration, $this->paymentControlOrderDataHashCalculator);

        $this->checkResponse = $this->createMock(PaymentControlCheckResponseDto::class);

        $this->setUpCheckResponse();
        $this->setUpPaymetControlOrderData();
    }

    /**
     * @return void
     */
    private function setUpCheckResponse()
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

    /**
     * @return void
     */
    private function setUpPaymetControlOrderData()
    {
        $this->data = new PaymentControlOrderData();
        $this->data->paymentMethodId = self::PAYMENT_METHOD_ID;
        $this->data->personalData = $this->createMock(CustomerDataDto::class);
        $this->data->invoiceAddress = $this->createMock(InvoiceAddressDto::class);
        $this->data->deliveryAddress = $this->createMock(DeliveryAddressDto::class);
        $this->data->basket = $this->createMock(PaymentControlBasketDto::class);
    }

    /**
     * @param string|null $paymentTypeSecurity
     * @return void
     */
    private function setUpPaymentTypeSecurity($paymentTypeSecurity)
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

    /**
     * @return void
     * @param string $decision
     */
    private function setUpDecision($decision)
    {
        $decision = (string) $decision;
        $this->checkResponse->decision = $decision;
    }

    /**
     * @dataProvider dataProvider_test_check_returns_correct_payment_control_action
     * @param string|null $paymentTypeSecurity
     * @param string $decision
     * @param string $expectedAction
     * @return void
     */
    public function test_check_returns_correct_payment_control_action($paymentTypeSecurity, $decision, $expectedAction)
    {
        $this->setUpPaymentTypeSecurity($paymentTypeSecurity);
        $this->setUpDecision($decision);

        $actual = $this->sut->check($this->data, $this->paymentControlCache);

        $this->assertEquals($expectedAction, $actual);
    }

    /**
     * @return mixed[]
     */
    public function dataProvider_test_check_returns_correct_payment_control_action()
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

    /**
     * @return void
     */
    public function test_check_sends_request_mode_single_step()
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

    /**
     * @return void
     */
    public function test_check_sends_proof_of_interest_AAE()
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
     * @param string $paymentTypeSecurity
     * @return void
     */
    public function test_check_sends_correct_payment_type_security($paymentTypeSecurity)
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

    /**
     * @return mixed[]
     */
    public function dataProvider_test_check_sends_correct_payment_type_security()
    {
        return [
            [PaymentTypeSecurities::SAFE],
            [PaymentTypeSecurities::UNSAFE],
        ];
    }

    /**
     * @return void
     */
    public function test_check_sends_personal_data()
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

    /**
     * @return void
     */
    public function test_check_sends_invoice_address()
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

    /**
     * @return void
     */
    public function test_check_sends_delivery_address()
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

    /**
     * @return void
     */
    public function test_check_sends_basket()
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

    /**
     * @return void
     */
    public function test_check_throws_PaymentControlCheckFailedException()
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $this->paymentControlApi
            ->method('paymentControlCheck')
            ->willThrowException(new Exception());

        $this->expectException(PaymentControlCheckFailedException::class);
        $this->sut->check($this->data, $this->paymentControlCache);
    }

    /**
     * @return void
     */
    public function test_check_saves_response_in_chache()
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
     * @param string $paymentTypeSecurity
     * @return void
     */
    public function test_confirm_sends_correct_payment_type_security($paymentTypeSecurity)
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

    /**
     * @return mixed[]
     */
    public function dataProvider_test_confirm_sends_correct_payment_type_security()
    {
        return [
            [PaymentTypeSecurities::SAFE],
            [PaymentTypeSecurities::UNSAFE],
        ];
    }

    /**
     * @return void
     */
    public function test_confirm_sends_personal_data()
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

    /**
     * @return void
     */
    public function test_confirm_sends_invoice_address()
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

    /**
     * @return void
     */
    public function test_confirm_sends_delivery_address()
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

    /**
     * @return void
     */
    public function test_confirm_sends_basket()
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

    /**
     * @return void
     */
    public function test_confirm_sends_response()
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

    /**
     * @return void
     */
    public function test_confirm_throws_PaymentControlChonfirmFailedException()
    {
        $this->setUpPaymentTypeSecurity(PaymentTypeSecurities::SAFE);
        $this->setUpDecision(CheckDecisions::SAFE);

        $this->paymentControlApi
            ->method('paymentControlConfirm')
            ->willThrowException(new Exception());

        $this->expectException(PaymentControlConfirmFailedException::class);
        $this->sut->confirm($this->data, $this->paymentControlCache);
    }

    /**
     * @return void
     */
    public function test_check_returns_COMPLETE_ORDER_if_request_matches_previous_request_and_only_paymentMethod_is_changed()
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
