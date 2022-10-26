<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\Clients\Checkout;

use Axytos\ECommerce\Clients\Checkout\CheckoutApiAdapter;
use Axytos\FinancialServices\GuzzleHttp\ClientInterface;
use Axytos\FinancialServices\GuzzleHttp\Psr7\Request;
use Axytos\FinancialServices\OpenAPI\Client\Api\StaticContentApi;
use Axytos\FinancialServices\Psr\Http\Message\ResponseInterface;
use Axytos\FinancialServices\Psr\Http\Message\StreamInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CheckoutApiAdapterTest extends TestCase
{
    /** @var ClientInterface&MockObject */
    private ClientInterface $client;

    /** @var StaticContentApi&MockObject */
    private StaticContentApi $staticContentApi;

    private CheckoutApiAdapter $sut;

    public function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->staticContentApi = $this->createMock(StaticContentApi::class);

        $this->sut = new CheckoutApiAdapter(
            $this->client,
            $this->staticContentApi
        );
    }

    public function test_getCreditCheckAgreementText_returns_request_body_contents(): void
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
