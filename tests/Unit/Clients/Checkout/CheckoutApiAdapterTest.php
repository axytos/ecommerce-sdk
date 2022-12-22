<?php

namespace Axytos\ECommerce\Tests\Unit\Clients\Checkout;

use Axytos\ECommerce\Clients\Checkout\CheckoutApiAdapter;
use Axytos\ECommerce\Clients\Checkout\StaticContentApiProxy;
use Axytos\FinancialServices\GuzzleHttp\ClientInterface;
use Axytos\FinancialServices\GuzzleHttp\Psr7\Request;
use Axytos\FinancialServices\Psr\Http\Message\ResponseInterface;
use Axytos\FinancialServices\Psr\Http\Message\StreamInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CheckoutApiAdapterTest extends TestCase
{
    /** @var ClientInterface&MockObject */
    private $client;

    /** @var StaticContentApiProxy&MockObject */
    private $staticContentApi;

    /**
     * @var \Axytos\ECommerce\Clients\Checkout\CheckoutApiAdapter
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->staticContentApi = $this->createMock(StaticContentApiProxy::class);

        $this->sut = new CheckoutApiAdapter(
            $this->client,
            $this->staticContentApi
        );
    }

    /**
     * @return void
     */
    public function test_getCreditCheckAgreementText_returns_request_body_contents()
    {
        $contents = 'contents';

        /** @var StreamInterface&MockObject */
        $body = $this->createMock(StreamInterface::class);
        $body->method('getContents')->willReturn($contents);

        /** @var ResponseInterface&MockObject */
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($body);

        /** @var Request&MockObject */
        $request = $this->createMock(Request::class);

        $this->staticContentApi
            ->method('apiV1StaticContentCreditcheckagreementGetRequest')
            ->willReturn($request);

        $this->client
            ->method('send')
            ->with($request)
            ->willReturn($response);

        $actual = $this->sut->getCreditCheckAgreementText();

        $this->assertSame($contents, $actual);
    }
}
