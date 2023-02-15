<?php

namespace Axytos\ECommerce\Tests\Integration;

use Axytos\ECommerce\AxytosECommerceClient;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\ShopActions;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\Tests\Integration\Fakes\InvoiceOrderContextFakeFactory;
use Axytos\ECommerce\Tests\Integration\Providers\ApiHostProvider;
use Axytos\ECommerce\Tests\Integration\Providers\ApiKeyProvider;
use Axytos\ECommerce\Tests\Integration\Providers\FallbackModeConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\PaymentMethodConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\UserAgentInfoProvider;
use PHPUnit\Framework\TestCase;

class InvoiceClientIntegrationTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface
     */
    private $invoiceClient;

    /**
     * @var \Axytos\ECommerce\Tests\Integration\Fakes\InvoiceOrderContextFake
     */
    private $orderContext;

    /**
     * @var InvoiceOrderContextFakeFactory
     */
    private $invoiceOrderContextFactory;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->invoiceClient = new AxytosECommerceClient(new ApiHostProvider(), new ApiKeyProvider(), new PaymentMethodConfiguration(), new FallbackModeConfiguration(), new UserAgentInfoProvider(), $this->createMock(LoggerAdapterInterface::class));

        $this->invoiceOrderContextFactory = new InvoiceOrderContextFakeFactory();
        $this->orderContext = $this->invoiceOrderContextFactory->createInvoiceOrderContext();
    }

    /**
     * @return void
     */
    public function test_precheck_confirm()
    {
        $this->assertEmpty($this->orderContext->getPreCheckResponseData());

        $shopAction = $this->invoiceClient->precheck($this->orderContext);

        $this->assertEquals(ShopActions::COMPLETE_ORDER, $shopAction);
        $this->assertNotEmpty($this->orderContext->getPreCheckResponseData());
    }

    /**
     * @return void
     */
    public function test_precheck_confirm_createInvoice_shipping_return_refund()
    {
        $this->invoiceClient->precheck($this->orderContext);

        $this->invoiceClient->confirmOrder($this->orderContext);

        $this->invoiceClient->createInvoice($this->orderContext);

        $this->invoiceClient->reportShipping($this->orderContext);

        $this->invoiceClient->trackingInformation($this->orderContext);

        $this->invoiceClient->returnOrder($this->orderContext);

        $this->invoiceClient->refund($this->orderContext);

        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function test_precheck_confirm_cancel()
    {

        $this->invoiceClient->precheck($this->orderContext);

        $this->invoiceClient->confirmOrder($this->orderContext);

        $this->invoiceClient->cancelOrder($this->orderContext);

        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function test_precheck_confirm_update()
    {
        $this->invoiceClient->precheck($this->orderContext);

        $this->invoiceClient->confirmOrder($this->orderContext);

        $this->invoiceClient->updateOrder($this->orderContext);

        $this->orderContext->setBasket($this->invoiceOrderContextFactory->createBasket());

        $this->invoiceClient->updateOrder($this->orderContext);

        $this->assertTrue(true);
    }
}
