<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Integration;

use Axytos\ECommerce\AxytosECommerceClient;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Invoice\ShopActions;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\Tests\Integration\Providers\ApiHostProvider;
use Axytos\ECommerce\Tests\Integration\Providers\ApiKeyProvider;
use Axytos\ECommerce\Tests\Integration\Providers\FallbackModeConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\PaymentMethodConfiguration;
use Axytos\ECommerce\Tests\Integration\Providers\UserAgentInfoProvider;
use PHPUnit\Framework\TestCase;

class InvoiceClientIntegrationTest extends TestCase
{
    private InvoiceClientInterface $invoiceClient;

    private InvoiceOrderContext $orderContext;

    public function setUp(): void
    {
        $this->invoiceClient = new AxytosECommerceClient(
            new ApiHostProvider(),
            new ApiKeyProvider(),
            new PaymentMethodConfiguration(),
            new FallbackModeConfiguration(),
            new UserAgentInfoProvider(),
            $this->createMock(LoggerAdapterInterface::class),
        );

        $invoiceOrderContextFactory = new InvoiceOrderContextFactory();
        $this->orderContext = $invoiceOrderContextFactory->createInvoiceOrderContext();
    }

    public function test_precheck_confirm(): void
    {
        $this->assertEmpty($this->orderContext->getPreCheckResponseData());

        $shopAction = $this->invoiceClient->precheck($this->orderContext);

        $this->assertEquals(ShopActions::COMPLETE_ORDER, $shopAction);
        $this->assertNotEmpty($this->orderContext->getPreCheckResponseData());
    }

    public function test_precheck_confirm_createInvoice_shipping_return_refund(): void
    {
        $this->invoiceClient->precheck($this->orderContext);

        $this->invoiceClient->confirmOrder($this->orderContext);

        $this->invoiceClient->createInvoice($this->orderContext);

        $this->invoiceClient->reportShipping($this->orderContext);

        $this->invoiceClient->return($this->orderContext);

        $this->invoiceClient->refund($this->orderContext);

        $this->assertTrue(true);
    }

    public function test_precheck_confirm_cancel(): void
    {

        $this->invoiceClient->precheck($this->orderContext);

        $this->invoiceClient->confirmOrder($this->orderContext);

        $this->invoiceClient->cancelOrder($this->orderContext);

        $this->assertTrue(true);
    }
}
