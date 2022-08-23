<?php declare(strict_types=1);

namespace Axytos\ECommerce\Tests\CredentialValidation;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;
use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;
use Axytos\ECommerce\Abstractions\FallbackModeConfigurationInterface;
use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;
use Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface;
use Axytos\ECommerce\AxytosECommerceClient;
use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use PHPUnit\Framework\TestCase;

class AxytosECommerceClientTest extends TestCase
{
    private ApiHostProviderInterface $apiHostProvider;
    private ApiKeyProviderInterface $apiKeyProvider;
    private PaymentMethodConfigurationInterface $paymentMethodConfiguration;
    private FallbackModeConfigurationInterface $fallbackModeConfiguration;
    private UserAgentInfoProviderInterface $userAgentInfoProvider;
    private LoggerAdapterInterface $logger;

    public function setUp(): void
    {
        $this->apiHostProvider = $this->createMock(ApiHostProviderInterface::class);
        $this->apiKeyProvider = $this->createMock(ApiKeyProviderInterface::class);
        $this->paymentMethodConfiguration = $this->createMock(PaymentMethodConfigurationInterface::class);
        $this->fallbackModeConfiguration = $this->createMock(FallbackModeConfigurationInterface::class);
        $this->userAgentInfoProvider = $this->createMock(UserAgentInfoProviderInterface::class);
        $this->logger = $this->createMock(LoggerAdapterInterface::class);
    }

    private function buildContainer(): Container
    {
        return AxytosECommerceClient::buildContainer(
            $this->apiHostProvider,
            $this->apiKeyProvider,
            $this->paymentMethodConfiguration,
            $this->fallbackModeConfiguration,
            $this->userAgentInfoProvider,
            $this->logger
        );
    }

    /** @return array */
    private function getExpectedContainderIds()
    {
        $result = [];

        foreach(AxytosECommerceClient::CLASS_MAP as $className => $aliasNames)
        {
            $result = array_merge($result, $aliasNames);
        }

        return $result;
    }

    public function test_AxytosECommerceClient_can_be_constructed(): void
    {
        $client = new AxytosECommerceClient(
            $this->apiHostProvider,
            $this->apiKeyProvider,
            $this->paymentMethodConfiguration,
            $this->fallbackModeConfiguration,
            $this->userAgentInfoProvider,
            $this->logger,
        );
        $this->assertNotNull($client);
    }

    public function test_buildContainer_all_classes_registered(): void
    {
        $container = $this->buildContainer();

        $ids = $this->getExpectedContainderIds();

        foreach ($ids as $id)
        {
            $this->assertTrue($container->has($id));
        }
    }

    public function test_buildContainer_all_registered_classes_can_be_resolved(): void
    {
        $container = $this->buildContainer();
        
        /** @var string[] $keys */
        $keys = $container->keys();

        foreach ($keys as $key)
        {
            if (!class_exists($key)) {
                return;
            }
            
            $instance = $container->get($key);
            $this->assertInstanceOf($key, $instance);
        }
    }

    public function test_buildContainer_ApiHostProviderInterface_can_be_resolved(): void
    {
        $container = $this->buildContainer();

        $instance = $container->get(ApiHostProviderInterface::class);

        $this->assertInstanceOf(ApiHostProviderInterface::class, $instance);
        $this->assertSame($this->apiHostProvider, $instance);
    }

    public function test_ApiKeyProviderInterface_can_be_resolved(): void
    {
        $container = $this->buildContainer();

        $instance = $container->get(ApiKeyProviderInterface::class);

        $this->assertInstanceOf(ApiKeyProviderInterface::class, $instance);
        $this->assertSame($this->apiKeyProvider, $instance);
    }

    public function test_PaymentMethodConfigurationInterface_can_be_resolved(): void
    {
        $container = $this->buildContainer();

        $instance = $container->get(PaymentMethodConfigurationInterface::class);

        $this->assertInstanceOf(PaymentMethodConfigurationInterface::class, $instance);
        $this->assertSame($this->paymentMethodConfiguration, $instance);
    }

    public function test_FallbackModeConfigurationInterface_can_be_resolved(): void
    {
        $container = $this->buildContainer();

        $instance = $container->get(FallbackModeConfigurationInterface::class);

        $this->assertInstanceOf(FallbackModeConfigurationInterface::class, $instance);
        $this->assertSame($this->fallbackModeConfiguration, $instance);
    }

    public function test_UserAgentInfoProviderInterface_can_be_resolved(): void
    {
        $container = $this->buildContainer();

        $instance = $container->get(UserAgentInfoProviderInterface::class);

        $this->assertInstanceOf(UserAgentInfoProviderInterface::class, $instance);
        $this->assertSame($this->userAgentInfoProvider, $instance);
    }
}
