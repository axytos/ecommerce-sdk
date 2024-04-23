<?php

namespace Axytos\ECommerce\Tests\Unit\Clients\CredentialValidation;

use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClient;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CredentialValidationClientTest extends TestCase
{
    /** @var CredentialValidationApiInterface&MockObject */
    private $credentialValidationApi;

    /**
     * @var \Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClient
     */
    private $sut;

    /**
     * @return void
     * @before
     */
    #[Before]
    public function beforeEach()
    {
        $this->credentialValidationApi = $this->createMock(CredentialValidationApiInterface::class);
        $this->sut = new CredentialValidationClient(
            $this->credentialValidationApi
        );
    }

    /**
     * @return void
     */
    public function test_validateApiKey_returns_true()
    {
        $this->credentialValidationApi
            ->method('getCredentialsValidation')
            ->willReturn(true);

        $result = $this->sut->validateApiKey();

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function test_validateApiKey_returns_false()
    {
        $this->credentialValidationApi
            ->method('getCredentialsValidation')
            ->willReturn(false);

        $result = $this->sut->validateApiKey();

        $this->assertFalse($result);
    }
}
