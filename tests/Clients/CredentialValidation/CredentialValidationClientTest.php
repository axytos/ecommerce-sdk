<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\Clients\CredentialValidation;

use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClient;
use PHPUnit\Framework\TestCase;
use \PHPUnit\Framework\MockObject\MockObject;

class CredentialValidationClientTest extends TestCase
{
    /** @var CredentialValidationApiInterface|MockObject $CredentialValidationApi */
    private CredentialValidationApiInterface $CredentialValidationApi;

    private CredentialValidationClient $sut;

    public function setUp(): void
    {
        $this->CredentialValidationApi = $this->createMock(CredentialValidationApiInterface::class);
        $this->sut = new CredentialValidationClient(
            $this->CredentialValidationApi
        );
    }

    public function test_validateApiKey_returns_true(): void
    {
        $this->CredentialValidationApi
            ->method('getCredentialsValidation')
            ->willReturn(true);

        $result = $this->sut->validateApiKey();

        $this->assertTrue($result);
    }

    public function test_validateApiKey_returns_false(): void
    {
        $this->CredentialValidationApi
            ->method('getCredentialsValidation')
            ->willReturn(false);

        $result = $this->sut->validateApiKey();

        $this->assertFalse($result);
    }
}
