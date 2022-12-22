<?php

namespace Axytos\ECommerce\Tests\Unit\Clients\CredentialValidation;

use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiAdapter;
use Axytos\FinancialServices\OpenAPI\Client\Api\CredentialsApi;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CredentialValidationApiAdapterTest extends TestCase
{
    /** @var CredentialsApi&MockObject */
    private $credentialsApi;

    /**
     * @var \Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiAdapter
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->credentialsApi = $this->createMock(CredentialsApi::class);
        $this->sut = new CredentialValidationApiAdapter(
            $this->credentialsApi
        );
    }

    /**
     * @return void
     */
    public function test_getCredentialValidation_calls_api()
    {
        $this->credentialsApi
            ->expects($this->once())
            ->method('apiV1CredentialsValidateGetWithHttpInfo');

        $this->sut->getCredentialsValidation();
    }

    /**
     * @return void
     */
    public function test_getCredentialValidation_returns_true_if_api_call_succeeds()
    {
        $this->assertTrue($this->sut->getCredentialsValidation());
    }

    /**
     * @return void
     */
    public function test_getCredentialValidation_returns_falls_if_api_call_fails()
    {
        $this->credentialsApi->method('apiV1CredentialsValidateGetWithHttpInfo')->willThrowException(new ApiException());

        $this->assertFalse($this->sut->getCredentialsValidation());
    }
}
