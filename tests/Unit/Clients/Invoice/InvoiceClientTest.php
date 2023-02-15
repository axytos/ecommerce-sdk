<?php

namespace Axytos\ECommerce\Tests\Unit\Clients\Invoice;

use Axytos\ECommerce\Clients\Invoice\InvoiceApiInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClient;
use Axytos\ECommerce\Clients\Invoice\InvoiceOrderContextInterface;
use Axytos\ECommerce\Clients\Invoice\ShopActions;
use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataTransferObjects\BasketDto;
use Axytos\ECommerce\DataTransferObjects\CustomerDataDto;
use Axytos\ECommerce\DataTransferObjects\DeliveryAddressDto;
use Axytos\ECommerce\DataTransferObjects\InvoiceAddressDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderPreCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\CheckDecisions;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceBasketDto;
use Axytos\ECommerce\DataTransferObjects\CreateInvoiceRequestDto;
use Axytos\ECommerce\DataTransferObjects\OrderCreateRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentStateResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentTypeSecurities;
use Axytos\ECommerce\DataTransferObjects\RefundBasketDto;
use Axytos\ECommerce\DataTransferObjects\RefundRequestDto;
use Axytos\ECommerce\DataTransferObjects\ReportShippingDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection;
use Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto;
use Axytos\ECommerce\DataTransferObjects\UpdateOrderModelDto;
use DateTime;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class InvoiceClientTest extends TestCase
{
    /** @var InvoiceApiInterface&MockObject */
    private $invoiceApi;

    /** @var DtoArrayMapper&MockObject */
    private $dtoArrayMapper;

    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClient
     */
    private $sut;

    /** @var OrderPreCheckResponseDto&MockObject */
    private $response;

    /** @var array */
    private $responseData;

    /** @var InvoiceOrderContextInterface&MockObject */
    private $invoiceOrderContext;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->invoiceApi = $this->createMock(InvoiceApiInterface::class);
        $this->dtoArrayMapper = $this->createMock(DtoArrayMapper::class);

        $this->sut = new InvoiceClient(
            $this->invoiceApi,
            $this->dtoArrayMapper
        );

        $this->response = $this->createMock(OrderPreCheckResponseDto::class);
        $this->responseData = [];
        $this->invoiceOrderContext = $this->createMock(InvoiceOrderContextInterface::class);

        $this->setUpResponse();
        $this->setUpInvoiceOrderContext();
        $this->setUpDtoArrayMapper();
    }

    /**
     * @return void
     */
    private function setUpResponse()
    {
        $this->invoiceApi
            ->method('precheck')
            ->willReturn($this->response);
    }

    /**
     * @return void
     */
    private function setUpInvoiceOrderContext()
    {
        $orderNumber = 'orderNumber';
        $orderInvoiceNumber = 'orderInvoiceNumber';
        $orderDate = $this->createMock(DateTime::class);
        $personalData = $this->createMock(CustomerDataDto::class);
        $invoiceAddress = $this->createMock(InvoiceAddressDto::class);
        $deliveryAddress = $this->createMock(DeliveryAddressDto::class);
        $basket = $this->createMock(BasketDto::class);
        $refundBasket = $this->createMock(RefundBasketDto::class);
        $createInvoiceBasketDto = $this->createMock(CreateInvoiceBasketDto::class);
        $returnPositions = $this->createMock(ReturnPositionModelDtoCollection::class);

        $this->invoiceOrderContext->method('getOrderNumber')->willReturn($orderNumber);
        $this->invoiceOrderContext->method('getOrderInvoiceNumber')->willReturn($orderInvoiceNumber);
        $this->invoiceOrderContext->method('getOrderDateTime')->willReturn($orderDate);
        $this->invoiceOrderContext->method('getPersonalData')->willReturn($personalData);
        $this->invoiceOrderContext->method('getInvoiceAddress')->willReturn($invoiceAddress);
        $this->invoiceOrderContext->method('getDeliveryAddress')->willReturn($deliveryAddress);
        $this->invoiceOrderContext->method('getBasket')->willReturn($basket);
        $this->invoiceOrderContext->method('getRefundBasket')->willReturn($refundBasket);
        $this->invoiceOrderContext->method('getCreateInvoiceBasket')->willReturn($createInvoiceBasketDto);
        $this->invoiceOrderContext->method('getPreCheckResponseData')->willReturn($this->responseData);
        $this->invoiceOrderContext->method('getReturnPositions')->willReturn($returnPositions);
    }

    /**
     * @return void
     */
    private function setUpDtoArrayMapper()
    {
        $this->dtoArrayMapper
            ->method('toArray')
            ->with($this->response)
            ->willReturn($this->responseData);

        $this->dtoArrayMapper
            ->method('fromArray')
            ->with($this->responseData, OrderPreCheckResponseDto::class)
            ->willReturn($this->response);
    }

    /**
     * @return void
     * @param string $decision
     */
    private function setUpDecision($decision)
    {
        $decision = (string) $decision;
        $this->response->decision = $decision;
    }

    /**
     * @phpstan-param mixed $value
     * @return void
     * @param string $propertyName
     */
    private function expectOrderPreCheckRequestValueEquals($propertyName, $value)
    {
        $propertyName = (string) $propertyName;
        $matcher = $this->callback(function (OrderPreCheckRequestDto $requestDto) use ($propertyName, $value) {
            return $requestDto->$propertyName === $value;
        });

        $this->invoiceApi
            ->expects($this->once())
            ->method('precheck')
            ->with($matcher);
    }

    /**
     * @phpstan-param mixed $value
     * @return void
     * @param string $propertyName
     */
    private function expectOrderCreateRequestValueEquals($propertyName, $value)
    {
        $propertyName = (string) $propertyName;
        $matcher = $this->callback(function (OrderCreateRequestDto $requestDto) use ($propertyName, $value) {
            return $requestDto->$propertyName === $value;
        });

        $this->invoiceApi
            ->expects($this->once())
            ->method('confirm')
            ->with($matcher);
    }

    /**
     * @return void
     */
    public function test_precheck_sends_requestMode_SingleStep()
    {
        $this->expectOrderPreCheckRequestValueEquals('requestMode', 'SingleStep');

        $this->sut->precheck($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_precheck_sends_proofOfInterest_AAE()
    {
        $this->expectOrderPreCheckRequestValueEquals('proofOfInterest', 'AAE');

        $this->sut->precheck($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_precheck_sends_selectedPaymentType_AAE()
    {
        $this->expectOrderPreCheckRequestValueEquals('selectedPaymentType', 'INVOICE');

        $this->sut->precheck($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_precheck_sends_paymentTypeSecurity_UNSAFE()
    {
        $this->expectOrderPreCheckRequestValueEquals('paymentTypeSecurity', PaymentTypeSecurities::UNSAFE);

        $this->sut->precheck($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_precheck_sends_personal_data()
    {
        $this->expectOrderPreCheckRequestValueEquals('personalData', $this->invoiceOrderContext->getPersonalData());

        $this->sut->precheck($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_precheck_sends_invoice_address()
    {
        $this->expectOrderPreCheckRequestValueEquals('invoiceAddress', $this->invoiceOrderContext->getInvoiceAddress());

        $this->sut->precheck($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_precheck_sends_delivery_address()
    {
        $this->expectOrderPreCheckRequestValueEquals('deliveryAddress', $this->invoiceOrderContext->getDeliveryAddress());

        $this->sut->precheck($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_precheck_sends_basket()
    {
        $this->expectOrderPreCheckRequestValueEquals('basket', $this->invoiceOrderContext->getBasket());

        $this->sut->precheck($this->invoiceOrderContext);
    }

    /**
     * @dataProvider dataProvider_test_precheck_returns_expected_action
     * @param string $decision
     * @param string $expectedAction
     * @return void
     */
    public function test_precheck_returns_expected_action($decision, $expectedAction)
    {
        $this->setUpDecision($decision);

        $actual = $this->sut->precheck($this->invoiceOrderContext);

        $this->assertEquals($expectedAction, $actual);
    }

    /**
     * @return mixed[]
     */
    public function dataProvider_test_precheck_returns_expected_action()
    {
        return [
            [CheckDecisions::SAFE, ShopActions::CHANGE_PAYMENT_METHOD],
            [CheckDecisions::REJECT, ShopActions::CHANGE_PAYMENT_METHOD],
            [CheckDecisions::UNSAFE, ShopActions::COMPLETE_ORDER],
        ];
    }

    /**
     * @return void
     */
    public function test_precheck_saves_response()
    {
        $this->invoiceOrderContext
            ->expects($this->once())
            ->method('setPreCheckResponseData')
            ->with($this->responseData);

        $this->sut->precheck($this->invoiceOrderContext);
    }

    //==================================================================================================================
    // Confirm
    /**
     * @return void
     */
    public function test_confirmOrder_sends_order_number()
    {
        $this->expectOrderCreateRequestValueEquals('externalOrderId', $this->invoiceOrderContext->getOrderNumber());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_confirmOrder_sends_order_date_time()
    {
        $this->expectOrderCreateRequestValueEquals('date', $this->invoiceOrderContext->getOrderDateTime());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_confirmOrder_sends_personal_data()
    {
        $this->expectOrderCreateRequestValueEquals('personalData', $this->invoiceOrderContext->getPersonalData());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_confirmOrder_sends_invoice_address()
    {
        $this->expectOrderCreateRequestValueEquals('invoiceAddress', $this->invoiceOrderContext->getInvoiceAddress());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_confirmOrder_sends_delivery_address()
    {
        $this->expectOrderCreateRequestValueEquals('deliveryAddress', $this->invoiceOrderContext->getDeliveryAddress());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_confirmOrder_sends_basket()
    {
        $this->expectOrderCreateRequestValueEquals('basket', $this->invoiceOrderContext->getBasket());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }

    /**
     * @return void
     */
    public function test_confirmOrder_sends_precheck_response()
    {
        $this->expectOrderCreateRequestValueEquals('orderPrecheckResponse', $this->response);

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }

    //==================================================================================================================
    // Cancel
    /**
     * @return void
     */
    public function test_cancelOrder_calls_API()
    {
        $this->invoiceApi
            ->expects($this->once())
            ->method('cancelOrder')
            ->with($this->invoiceOrderContext->getOrderNumber());

        $this->sut->cancelOrder($this->invoiceOrderContext);
    }



    //==================================================================================================================
    // Refund
    /**
     * @return void
     */
    public function test_refund_calls_API()
    {
        $refundRequestDto = new RefundRequestDto();
        $refundRequestDto->externalOrderId = $this->invoiceOrderContext->getOrderNumber();
        $refundRequestDto->originalInvoiceNumber = $this->invoiceOrderContext->getOrderInvoiceNumber();
        $refundRequestDto->basket = $this->invoiceOrderContext->getRefundBasket();

        $this->invoiceApi
            ->expects($this->once())
            ->method('refund')
            ->with($this->equalTo($refundRequestDto));

        $this->sut->refund($this->invoiceOrderContext);
    }

    //==================================================================================================================
    // Create Invoice
    /**
     * @return void
     */
    public function test_createInvoice_calls_API()
    {
        $createInvoiceRequestDto = new CreateInvoiceRequestDto();
        $createInvoiceRequestDto->basket = $this->invoiceOrderContext->getCreateInvoiceBasket();
        $createInvoiceRequestDto->externalInvoiceNumber = $this->invoiceOrderContext->getOrderInvoiceNumber();
        $createInvoiceRequestDto->externalOrderId = $this->invoiceOrderContext->getOrderNumber();

        $this->invoiceApi
            ->expects($this->once())
            ->method('createInvoice')
            ->with($this->equalTo($createInvoiceRequestDto));

        $this->sut->createInvoice($this->invoiceOrderContext);
    }

    //==================================================================================================================
    // Return Order
    /**
     * @return void
     */
    public function test_return_calls_API()
    {
        $requestDto = new ReturnRequestModelDto();
        $requestDto->externalOrderId = $this->invoiceOrderContext->getOrderNumber();
        $requestDto->positions = $this->invoiceOrderContext->getReturnPositions();

        $this->invoiceApi
            ->expects($this->once())
            ->method('returnOrder')
            ->with($this->equalTo($requestDto));

        $this->sut->returnOrder($this->invoiceOrderContext);
    }

    //==================================================================================================================
    // Report Shipping
    /**
     * @return void
     */
    public function test_reportShipping_calls_API()
    {
        $requestDto = new ReportShippingDto();
        $requestDto->externalOrderId = $this->invoiceOrderContext->getOrderNumber();
        $requestDto->basketPositions = $this->invoiceOrderContext->getShippingBasketPositions();

        $this->invoiceApi
            ->expects($this->once())
            ->method('reportShipping')
            ->with($this->equalTo($requestDto));

        $this->sut->reportShipping($this->invoiceOrderContext);
    }

    //==================================================================================================================
    // Payment Update
    /**
     * @return void
     */
    public function test_getInvoiceOrderPaymentUpdate_calls_API()
    {
        $paymentId = 'paymentId';

        $paymentResponseDto = new PaymentResponseDto();
        $paymentResponseDto->externalOrderId = 'externalOrderId';

        $paymentStateResponseDto = new PaymentStateResponseDto();
        $paymentStateResponseDto->paymentState = 'paymentState';

        $this->invoiceApi
            ->expects($this->once())
            ->method('payment')
            ->with($paymentId)
            ->willReturn($paymentResponseDto);

        $this->invoiceApi
            ->expects($this->once())
            ->method('paymentState')
            ->with($paymentResponseDto->externalOrderId)
            ->willReturn($paymentStateResponseDto);

        $paymentUpdate = $this->sut->getInvoiceOrderPaymentUpdate($paymentId);

        $this->assertEquals($paymentResponseDto->externalOrderId, $paymentUpdate->orderId);
        $this->assertEquals($paymentStateResponseDto->paymentState, $paymentUpdate->paymentStatus);
    }

    /**
     * @return void
     */
    public function test_getInvoiceOrderPaymentUpdate_throws_when_externalOrderId_is_null()
    {
        $paymentId = 'paymentId';

        $paymentResponseDto = new PaymentResponseDto();
        $paymentResponseDto->externalOrderId = null;

        $paymentStateResponseDto = new PaymentStateResponseDto();
        $paymentStateResponseDto->paymentState = 'paymentState';

        $this->invoiceApi
            ->method('payment')
            ->with($paymentId)
            ->willReturn($paymentResponseDto);

        $this->invoiceApi
            ->method('paymentState')
            ->with($paymentResponseDto->externalOrderId)
            ->willReturn($paymentStateResponseDto);


        $this->expectExceptionMessage('ExternalOrderId not found');

        $this->sut->getInvoiceOrderPaymentUpdate($paymentId);
    }

    /**
     * @return void
     */
    public function test_getInvoiceOrderPaymentUpdate_throws_when_paymentState_is_null()
    {
        $paymentId = 'paymentId';

        $paymentResponseDto = new PaymentResponseDto();
        $paymentResponseDto->externalOrderId = 'externalOrderId';

        $paymentStateResponseDto = new PaymentStateResponseDto();
        $paymentStateResponseDto->paymentState = null;

        $this->invoiceApi
            ->method('payment')
            ->with($paymentId)
            ->willReturn($paymentResponseDto);

        $this->invoiceApi
            ->method('paymentState')
            ->with($paymentResponseDto->externalOrderId)
            ->willReturn($paymentStateResponseDto);

        $this->expectExceptionMessage('PaymentState not found');

        $this->sut->getInvoiceOrderPaymentUpdate($paymentId);
    }

    //==================================================================================================================
    // Update Order
    /**
     * @return void
     */
    public function test_updateOrder_calls_API()
    {
        $requestDto = new UpdateOrderModelDto();
        $requestDto->externalOrderId = $this->invoiceOrderContext->getOrderNumber();
        $requestDto->basket = $this->invoiceOrderContext->getBasket();

        $this->invoiceApi
            ->expects($this->once())
            ->method('updateOrder')
            ->with($this->equalTo($requestDto));

        $this->sut->updateOrder($this->invoiceOrderContext);
    }
}
