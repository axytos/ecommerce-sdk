<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Clients\Invoice;

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
use Axytos\ECommerce\DataTransferObjects\PaymentTypeSecurities;
use Axytos\ECommerce\DataTransferObjects\RefundBasketDto;
use Axytos\ECommerce\DataTransferObjects\RefundRequestDto;
use Axytos\ECommerce\DataTransferObjects\ReturnPositionModelDtoCollection;
use Axytos\ECommerce\DataTransferObjects\ReturnRequestModelDto;
use DateTime;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class InvoiceClientTest extends TestCase
{
    /** @var InvoiceApiInterface&MockObject */
    private InvoiceApiInterface $invoiceApi;

    /** @var DtoArrayMapper&MockObject */
    private DtoArrayMapper $dtoArrayMapper;

    private InvoiceClient $sut;

    /** @var OrderPreCheckResponseDto&MockObject */
    private OrderPreCheckResponseDto $response;
    
    /** @var array */
    private array $responseData;

    /** @var InvoiceOrderContextInterface&MockObject */
    private InvoiceOrderContextInterface $invoiceOrderContext;

    public function setUp(): void
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

    private function setUpResponse(): void
    {
        $this->invoiceApi
            ->method('precheck')
            ->willReturn($this->response);
    }

    private function setUpInvoiceOrderContext(): void
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

    private function setUpDtoArrayMapper(): void
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

    private function setUpDecision(string $decision): void
    {
        $this->response->decision = $decision;
    }

    /** 
     * @phpstan-param mixed $value
     */
    private function expectOrderPreCheckRequestValueEquals(string $propertyName, $value): void
    {
        $matcher = $this->callback(function(OrderPreCheckRequestDto $requestDto) use ($propertyName, $value) {
            return $requestDto->$propertyName === $value;
        });

        $this->invoiceApi
            ->expects($this->once())
            ->method('precheck')
            ->with($matcher);
    }

    /** 
     * @phpstan-param mixed $value
     */
    private function expectOrderCreateRequestValueEquals(string $propertyName, $value): void
    {
        $matcher = $this->callback(function(OrderCreateRequestDto $requestDto) use ($propertyName, $value) {
            return $requestDto->$propertyName === $value;
        });

        $this->invoiceApi
            ->expects($this->once())
            ->method('confirm')
            ->with($matcher);
    }

    public function test_precheck_sends_requestMode_SingleStep(): void
    {
        $this->expectOrderPreCheckRequestValueEquals('requestMode', 'SingleStep');

        $this->sut->precheck($this->invoiceOrderContext);
    }

    public function test_precheck_sends_proofOfInterest_AAE(): void
    {
        $this->expectOrderPreCheckRequestValueEquals('proofOfInterest', 'AAE');

        $this->sut->precheck($this->invoiceOrderContext);
    }

    public function test_precheck_sends_selectedPaymentType_AAE(): void
    {
        $this->expectOrderPreCheckRequestValueEquals('selectedPaymentType', 'INVOICE');

        $this->sut->precheck($this->invoiceOrderContext);
    }
    
    public function test_precheck_sends_paymentTypeSecurity_UNSAFE(): void
    {
        $this->expectOrderPreCheckRequestValueEquals('paymentTypeSecurity', PaymentTypeSecurities::UNSAFE);

        $this->sut->precheck($this->invoiceOrderContext);
    }
    
    public function test_precheck_sends_personal_data(): void
    {
        $this->expectOrderPreCheckRequestValueEquals('personalData', $this->invoiceOrderContext->getPersonalData());

        $this->sut->precheck($this->invoiceOrderContext);
    }
    
    public function test_precheck_sends_invoice_address(): void
    {
        $this->expectOrderPreCheckRequestValueEquals('invoiceAddress', $this->invoiceOrderContext->getInvoiceAddress());

        $this->sut->precheck($this->invoiceOrderContext);
    }
    
    public function test_precheck_sends_delivery_address(): void
    {
        $this->expectOrderPreCheckRequestValueEquals('deliveryAddress', $this->invoiceOrderContext->getDeliveryAddress());

        $this->sut->precheck($this->invoiceOrderContext);
    }
    
    public function test_precheck_sends_basket(): void
    {
        $this->expectOrderPreCheckRequestValueEquals('basket', $this->invoiceOrderContext->getBasket());

        $this->sut->precheck($this->invoiceOrderContext);
    }

    /**
     * @dataProvider dataProvider_test_precheck_returns_expected_action
     */
    public function test_precheck_returns_expected_action(string $decision, string $expectedAction): void
    {
        $this->setUpDecision($decision);

        $actual = $this->sut->precheck($this->invoiceOrderContext);

        $this->assertEquals($expectedAction, $actual);
    }

    public function dataProvider_test_precheck_returns_expected_action(): array
    {
        return [
            [CheckDecisions::SAFE, ShopActions::CHANGE_PAYMENT_METHOD],
            [CheckDecisions::REJECT, ShopActions::CHANGE_PAYMENT_METHOD],
            [CheckDecisions::UNSAFE, ShopActions::COMPLETE_ORDER],
        ];
    }

    public function test_precheck_saves_response(): void
    {
        $this->invoiceOrderContext
            ->expects($this->once())
            ->method('setPreCheckResponseData')
            ->with($this->responseData);

        $this->sut->precheck($this->invoiceOrderContext);
    }

    //==================================================================================================================
    // Confirm

    public function test_confirmOrder_sends_order_number(): void
    {
        $this->expectOrderCreateRequestValueEquals('externalOrderId', $this->invoiceOrderContext->getOrderNumber());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }

    public function test_confirmOrder_sends_order_date_time(): void
    {
        $this->expectOrderCreateRequestValueEquals('date', $this->invoiceOrderContext->getOrderDateTime());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }
    
    public function test_confirmOrder_sends_personal_data(): void
    {
        $this->expectOrderCreateRequestValueEquals('personalData', $this->invoiceOrderContext->getPersonalData());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }
    
    public function test_confirmOrder_sends_invoice_address(): void
    {
        $this->expectOrderCreateRequestValueEquals('invoiceAddress', $this->invoiceOrderContext->getInvoiceAddress());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }
    
    public function test_confirmOrder_sends_delivery_address(): void
    {
        $this->expectOrderCreateRequestValueEquals('deliveryAddress', $this->invoiceOrderContext->getDeliveryAddress());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }
    
    public function test_confirmOrder_sends_basket(): void
    {
        $this->expectOrderCreateRequestValueEquals('basket', $this->invoiceOrderContext->getBasket());

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }

    public function test_confirmOrder_sends_precheck_response(): void
    {
        $this->expectOrderCreateRequestValueEquals('orderPrecheckResponse', $this->response);

        $this->sut->confirmOrder($this->invoiceOrderContext);
    }

    //==================================================================================================================
    // Cancel

    public function test_cancelOrder_calls_API(): void
    {
        $this->invoiceApi
            ->expects($this->once())
            ->method('cancelOrder')
            ->with($this->invoiceOrderContext->getOrderNumber());

        $this->sut->cancelOrder($this->invoiceOrderContext);
    }

    

    //==================================================================================================================
    // Refund

    public function test_refund_calls_API(): void
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

    public function test_createInvoice_calls_API(): void
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

    public function test_return_calls_API(): void
    {
        $requestDto = new ReturnRequestModelDto();
        $requestDto->externalOrderId = $this->invoiceOrderContext->getOrderNumber();
        $requestDto->positions = $this->invoiceOrderContext->getReturnPositions();

        $this->invoiceApi
            ->expects($this->once())
            ->method('return')
            ->with($this->equalTo($requestDto));

        $this->sut->return($this->invoiceOrderContext);
    }
}
