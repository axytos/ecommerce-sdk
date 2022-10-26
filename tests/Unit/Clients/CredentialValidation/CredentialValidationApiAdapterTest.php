<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\Clients\CredentialValidation;

use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiAdapter;
use Axytos\FinancialServices\OpenAPI\Client\Api\CredentialsApi;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CredentialValidationApiAdapterTest extends TestCase
{
    /** @var CredentialsApi&MockObject */
    private CredentialsApi $credentialsApi;

    private CredentialValidationApiAdapter $sut;

    public function setUp(): void
    {
        $this->credentialsApi = $this->createMock(CredentialsApi::class);
        $this->sut = new CredentialValidationApiAdapter(
            $this->credentialsApi
        );
    }

    public function test_getCredentialValidation_calls_api(): void
    {
        $this->credentialsApi
            ->expects($this->once())
            ->method('apiV1CredentialsValidateGetWithHttpInfo');

        $this->sut->getCredentialsValidation();
    }

    public function test_getCredentialValidation_returns_true_if_api_call_succeeds(): void
    {
        $this->assertTrue($this->sut->getCredentialsValidation());
    }

    public function test_getCredentialValidation_returns_falls_if_api_call_fails(): void
    {
        $this->credentialsApi->method('apiV1CredentialsValidateGetWithHttpInfo')->willThrowException(new ApiException());

        $this->assertFalse($this->sut->getCredentialsValidation());
    }
}
