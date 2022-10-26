<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Unit\Clients\CredentialValidation;

use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClient;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CredentialValidationClientTest extends TestCase
{
    /** @var CredentialValidationApiInterface&MockObject */
    private CredentialValidationApiInterface $credentialValidationApi;

    private CredentialValidationClient $sut;

    public function setUp(): void
    {
        $this->credentialValidationApi = $this->createMock(CredentialValidationApiInterface::class);
        $this->sut = new CredentialValidationClient(
            $this->credentialValidationApi
        );
    }

    public function test_validateApiKey_returns_true(): void
    {
        $this->credentialValidationApi
            ->method('getCredentialsValidation')
            ->willReturn(true);

        $result = $this->sut->validateApiKey();

        $this->assertTrue($result);
    }

    public function test_validateApiKey_returns_false(): void
    {
        $this->credentialValidationApi
            ->method('getCredentialsValidation')
            ->willReturn(false);

        $result = $this->sut->validateApiKey();

        $this->assertFalse($result);
    }
}
